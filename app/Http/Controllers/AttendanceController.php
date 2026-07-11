<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\QrToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Generate a new QR token (called by TU Admin).
     */
    public function generateQr(Request $request)
    {
        // Pastikan hanya role TU yang bisa generate QR
        if (Auth::user()->role !== 'tu') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $tokenStr = Str::random(40);
        $expiresAt = now()->addMinutes(15);

        $qrToken = QrToken::create([
            'token' => $tokenStr,
            'created_by' => Auth::id(),
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'success' => true,
            'token' => $qrToken->token,
            'expires_at' => $qrToken->expires_at->toIso8601String(),
            'expires_in_seconds' => now()->diffInSeconds($expiresAt),
        ]);
    }

    /**
     * Get currently active QR token if it exists.
     */
    public function getActiveQr(Request $request)
    {
        $activeToken = QrToken::where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$activeToken) {
            return response()->json(['active' => false]);
        }

        return response()->json([
            'active' => true,
            'token' => $activeToken->token,
            'expires_at' => $activeToken->expires_at->toIso8601String(),
            'expires_in_seconds' => now()->diffInSeconds($activeToken->expires_at),
        ]);
    }

    /**
     * Process scanned QR code from Guru.
     */
    public function scanQr(Request $request)
    {
        // Pastikan hanya role Guru yang bisa melakukan scan
        if (Auth::user()->role !== 'guru') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'token' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $tokenStr = $request->input('token');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Cari token di database
        $qrToken = QrToken::where('token', $tokenStr)->first();

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau tidak dikenali oleh sistem.',
            ], 400);
        }

        // Cek apakah token sudah kedaluwarsa
        if ($qrToken->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code sudah kedaluwarsa. Silakan minta Admin TU untuk mengenerate ulang.',
            ], 400);
        }

        $today = Carbon::today()->toDateString();

        // Cek apakah guru sudah melakukan absensi hari ini (apapun statusnya)
        $existingAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->attendance_type !== 'alfa') {
            $statusText = 'menunggu validasi';
            if ($existingAttendance->status === 'approved') {
                $statusText = 'telah disetujui';
            } elseif ($existingAttendance->status === 'rejected') {
                $statusText = 'telah ditolak';
            }

            return response()->json([
                'success' => false,
                'message' => "Anda sudah mengirimkan absensi hari ini dan statusnya saat ini {$statusText}.",
            ], 400);
        }

        if ($existingAttendance && $existingAttendance->attendance_type === 'alfa') {
            $existingAttendance->update([
                'qr_token_id' => $qrToken->id,
                'attendance_type' => 'hadir',
                'scan_time' => now()->toTimeString(),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'status' => 'pending',
                'notes' => 'Diperbarui oleh Guru dari status Alpa otomatis.',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil terekam (memperbarui status Alpa otomatis) dan sekarang menunggu validasi oleh Admin.',
                'data' => [
                    'scan_time' => Carbon::parse($existingAttendance->scan_time)->format('H:i:s'),
                    'status' => $existingAttendance->status,
                ]
            ]);
        }

        // Simpan data absensi
        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'qr_token_id' => $qrToken->id,
            'date' => $today,
            'scan_time' => now()->toTimeString(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil terekam secara lokal dan sekarang menunggu validasi oleh Admin.',
            'data' => [
                'scan_time' => Carbon::parse($attendance->scan_time)->format('H:i:s'),
                'status' => $attendance->status,
            ]
        ]);
    }

    /**
     * Submit Sakit / Izin request (called by Guru).
     */
    public function submitIzinSakit(Request $request)
    {
        if (Auth::user()->role !== 'guru') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'type' => 'required|in:sakit,izin',
            'notes' => 'required_if:type,izin|nullable|string|max:255',
        ]);

        $today = Carbon::today()->toDateString();

        // Cek jika sudah ada catatan absensi hari ini (apapun status/tipenya)
        $existing = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->attendance_type !== 'alfa') {
            $statusLabel = 'menunggu validasi';
            if ($existing->status === 'approved') {
                $statusLabel = 'telah disetujui';
            } elseif ($existing->status === 'rejected') {
                $statusLabel = 'telah ditolak';
            }
            
            $typeLabel = $existing->attendance_type === 'hadir' ? 'hadir' : ($existing->attendance_type === 'sakit' ? 'sakit' : 'izin');

            return response()->json([
                'success' => false,
                'message' => "Anda sudah merekam permohonan/absensi {$typeLabel} hari ini ({$statusLabel}).",
            ], 400);
        }

        $label = $request->input('type') === 'sakit' ? 'Sakit' : 'Izin';

        if ($existing && $existing->attendance_type === 'alfa') {
            $existing->update([
                'attendance_type' => $request->input('type'),
                'scan_time' => now()->toTimeString(),
                'status' => 'pending',
                'notes' => 'Diperbarui oleh Guru dari status Alpa otomatis. Alasan: ' . ($request->input('notes') ?? '-'),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Permohonan {$label} berhasil diajukan (memperbarui status Alpa otomatis) dan menunggu validasi admin.",
                'data' => [
                    'scan_time' => Carbon::parse($existing->scan_time)->format('H:i:s'),
                    'status' => $existing->status,
                ]
            ]);
        }

        // Simpan permohonan Sakit / Izin
        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'attendance_type' => $request->input('type'),
            'date' => $today,
            'scan_time' => now()->toTimeString(),
            'latitude' => 0.0,
            'longitude' => 0.0,
            'status' => 'pending',
            'notes' => $request->input('notes'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Permohonan {$label} berhasil diajukan dan menunggu validasi admin.",
            'data' => [
                'scan_time' => Carbon::parse($attendance->scan_time)->format('H:i:s'),
                'status' => $attendance->status,
            ]
        ]);
    }

    /**
     * Validate attendance (approve/reject by Piket or TU).
     */
    public function validateAttendance(Request $request, Attendance $attendance)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['piket', 'tu'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string|max:255',
        ]);

        $attendance->update([
            'status' => $request->input('status'),
            'validated_by' => $user->id,
            'validated_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        $statusMessage = $attendance->status === 'approved' ? 'disetujui' : 'ditolak';

        return response()->json([
            'success' => true,
            'message' => "Absensi untuk {$attendance->user->name} berhasil {$statusMessage}.",
            'data' => [
                'id' => $attendance->id,
                'status' => $attendance->status,
                'validator_name' => $user->name,
                'validated_at' => now()->toTimeString(),
            ]
        ]);
    }
}
