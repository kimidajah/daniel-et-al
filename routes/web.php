<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
        Route::get('/dashboard/guru', function () {
            return view('dashboard.guru');
        })->name('dashboard.guru');
    });

    // Route khusus Admin Piket
    Route::middleware(['role:piket'])->group(function () {
        Route::get('/dashboard/piket', function () {
            return view('dashboard.piket');
        })->name('dashboard.piket');
    });

    // Route khusus Tata Usaha (TU)
    Route::middleware(['role:tu'])->group(function () {
        Route::get('/dashboard/tu', function () {
            return view('dashboard.tu');
        })->name('dashboard.tu');
    });

    // Route khusus Kepala Sekolah
    Route::middleware(['role:kepala_sekolah'])->group(function () {
        Route::get('/dashboard/kepala', function () {
            return view('dashboard.kepala');
        })->name('dashboard.kepala');
    });
});
