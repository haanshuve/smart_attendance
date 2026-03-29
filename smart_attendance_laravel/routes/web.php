<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController; // Pastikan kamu punya AuthController

// --- 1. RUTE PUBLIK (Bisa diakses tanpa login) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// API untuk IoT (Biasanya pakai API Key, bukan login user)
Route::get('/api/scan', [AttendanceController::class, 'scan']);
Route::post('/api/verify', [AttendanceController::class, 'verify']);

// --- 2. RUTE PROTECTED (Wajib Login) ---
// Semua rute di dalam grup ini akan mengecek FR-01
Route::middleware(['auth'])->group(function () {

    // Halaman Utama/Dashboard
    Route::get('/', [AttendanceController::class, 'index'])->name('dashboard');

    // Fitur Manajemen (FR-02, FR-03, FR-10)
    Route::get('/scanner', [AttendanceController::class, 'scanner']);
    Route::get('/reset-data', [AttendanceController::class, 'reset']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rute testing
Route::get('/welcome', function () {
    return view('welcome');
});
