<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Root route redirects to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard & Role Protected Routes
Route::middleware(['auth'])->group(function () {
    // Main dashboard router
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route khusus Guru
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/dashboard/guru', [DashboardController::class, 'guru'])->name('dashboard.guru');
        Route::post('/dashboard/guru/scan-qr', [AttendanceController::class, 'scanQr'])->name('guru.scan-qr');
    });

    // Route khusus Admin Piket
    Route::middleware(['role:piket'])->group(function () {
        Route::get('/dashboard/piket', [DashboardController::class, 'piket'])->name('dashboard.piket');
    });

    // Route khusus Tata Usaha (TU)
    Route::middleware(['role:tu'])->group(function () {
        Route::get('/dashboard/tu', [DashboardController::class, 'tu'])->name('dashboard.tu');
        Route::post('/dashboard/tu/generate-qr', [AttendanceController::class, 'generateQr'])->name('tu.generate-qr');
        Route::get('/dashboard/tu/active-qr', [AttendanceController::class, 'getActiveQr'])->name('tu.active-qr');
    });

    // Route khusus Kepala Sekolah
    Route::middleware(['role:kepala_sekolah'])->group(function () {
        Route::get('/dashboard/kepala', [DashboardController::class, 'kepala'])->name('dashboard.kepala');
    });

    // Route Validasi Absensi (bisa diakses Piket & TU)
    Route::post('/dashboard/attendance/{attendance}/validate', [AttendanceController::class, 'validateAttendance'])->name('attendance.validate');
});

