<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

// --- 1. RUTE PUBLIK (Tanpa Login) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// API untuk IoT (ESP32 / Scanner) - FR-06 & FR-11
Route::get('/api/scan', [AttendanceController::class, 'scan']);

// --- 2. RUTE PROTECTED (Wajib Login Admin - FR-01) ---
Route::middleware(['auth'])->group(function () {

    // Dashboard Utama (Monitoring)
    Route::get('/', [AttendanceController::class, 'index'])->name('dashboard');
    Route::get('/reset-data', [AttendanceController::class, 'reset']);

    // --- FITUR FR-02: MANAJEMEN PESERTA ---
    Route::get('/peserta', [AttendanceController::class, 'pesertaIndex'])->name('peserta.index');
    Route::post('/peserta/simpan', [AttendanceController::class, 'pesertaStore'])->name('peserta.store');
    Route::delete('/peserta/{id}', [AttendanceController::class, 'pesertaDestroy'])->name('peserta.destroy');

    // --- FITUR FR-03: MANAJEMEN ACARA (Daftarkan di sini agar tidak error) ---
    Route::get('/event', [AttendanceController::class, 'eventIndex'])->name('event.index');
    Route::post('/event/simpan', [AttendanceController::class, 'eventStore'])->name('event.store');

    // Fitur Lainnya (FR-10 / Scanner Web)
    Route::get('/scanner', [AttendanceController::class, 'scanner']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rute testing
Route::get('/welcome', function () {
    return view('welcome');
});
