<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Automatically mark teachers as Alfa (Absent) if they haven't submitted 
     * attendance (present), sick (sakit), or leave (izin) request after 08:00 AM today.
     */
    public static function autoMarkAlpha()
    {
        $today = Carbon::today()->toDateString();
        $nowTime = Carbon::now()->toTimeString();

        // Batas waktu presensi adalah jam 08:00:00 pagi
        if ($nowTime >= '08:00:00') {
            // Dapatkan semua Guru
            $teachers = User::where('role', 'guru')->get();

            foreach ($teachers as $teacher) {
                // Cek apakah sudah ada catatan kehadiran hari ini
                $exists = Attendance::where('user_id', $teacher->id)
                    ->whereDate('date', $today)
                    ->exists();

                if (!$exists) {
                    // Otomatis tandai Alfa (Absen)
                    Attendance::create([
                        'user_id' => $teacher->id,
                        'attendance_type' => 'alfa',
                        'date' => $today,
                        'scan_time' => '08:00:00',
                        'latitude' => 0.0,
                        'longitude' => 0.0,
                        'status' => 'approved', // Alfa otomatis berstatus approved
                        'notes' => 'Sistem: Otomatis Alpa karena tidak melakukan presensi/izin/sakit hingga batas waktu.',
                    ]);
                }
            }
        }
    }

    /**
     * Main dashboard router redirecting to role-specific dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'guru' => redirect()->route('dashboard.guru'),
            'piket' => redirect()->route('dashboard.piket'),
            'tu' => redirect()->route('dashboard.tu'),
            'kepala_sekolah' => redirect()->route('dashboard.kepala'),
            default => abort(403, 'Peran tidak dikenali.'),
        };
    }

    /**
     * Guru dashboard page view.
     */
    public function guru()
    {
        // Jalankan auto mark alpa jika batas waktu terlampaui
        self::autoMarkAlpha();

        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        $monthlyAttendanceCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('attendance_type', 'hadir')
            ->where('status', 'approved')
            ->count();
            
        return view('dashboard.guru', compact('todayAttendance', 'monthlyAttendanceCount'));
    }

    /**
     * Admin TU dashboard page view.
     */
    public function tu()
    {
        // Jalankan auto mark alpa jika batas waktu terlampaui
        self::autoMarkAlpha();

        $totalTeachers = User::where('role', 'guru')->count();
        $presentToday = Attendance::whereDate('date', Carbon::today())
            ->where('attendance_type', 'hadir')
            ->where('status', 'approved')
            ->count();
            
        $teachers = User::where('role', 'guru')
            ->with('teacherProfile')
            ->get();
            
        return view('dashboard.tu', compact('totalTeachers', 'presentToday', 'teachers'));
    }

    /**
     * Admin Piket dashboard page view.
     */
    public function piket()
    {
        // Jalankan auto mark alpa jika batas waktu terlampaui
        self::autoMarkAlpha();

        $today = Carbon::today()->toDateString();
        $totalTeachers = User::where('role', 'guru')->count();
        
        $presentToday = Attendance::whereDate('date', $today)
            ->where('attendance_type', 'hadir')
            ->where('status', 'approved')
            ->count();
            
        $pendingCount = Attendance::whereDate('date', $today)
            ->where('status', 'pending')
            ->count();
            
        $attendanceRate = $totalTeachers > 0 ? round(($presentToday / $totalTeachers) * 100) : 0;
        
        $pendingAttendances = Attendance::whereDate('date', $today)
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('scan_time')
            ->get();
            
        $validatedAttendances = Attendance::whereDate('date', $today)
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['user', 'validator'])
            ->orderBy('scan_time', 'desc')
            ->get();
            
        return view('dashboard.piket', compact(
            'presentToday', 'pendingCount', 'attendanceRate', 'totalTeachers',
            'pendingAttendances', 'validatedAttendances'
        ));
    }

    /**
     * Kepala Sekolah dashboard page view.
     */
    public function kepala()
    {
        // Jalankan auto mark alpa jika batas waktu terlampaui
        self::autoMarkAlpha();
        
        return view('dashboard.kepala');
    }
}
