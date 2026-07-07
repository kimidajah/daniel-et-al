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
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        $monthlyAttendanceCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'approved')
            ->count();
            
        return view('dashboard.guru', compact('todayAttendance', 'monthlyAttendanceCount'));
    }

    /**
     * Admin TU dashboard page view.
     */
    public function tu()
    {
        $totalTeachers = User::where('role', 'guru')->count();
        $presentToday = Attendance::whereDate('date', Carbon::today())
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
        $today = Carbon::today()->toDateString();
        $totalTeachers = User::where('role', 'guru')->count();
        
        $presentToday = Attendance::whereDate('date', $today)
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
        return view('dashboard.kepala');
    }
}
