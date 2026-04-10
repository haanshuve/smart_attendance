<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

// --- PUBLIC ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// API IoT
Route::get('/api/scan', [AttendanceController::class, 'scan']);

// --- PROTECTED ---
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [AttendanceController::class, 'index'])->name('dashboard');
    Route::get('/reset-data', [AttendanceController::class, 'reset']);

    // PESERTA
    Route::get('/peserta', [AttendanceController::class, 'pesertaIndex'])->name('peserta.index');
    Route::post('/peserta/simpan', [AttendanceController::class, 'pesertaStore'])->name('peserta.store');
    Route::delete('/peserta/{id}', [AttendanceController::class, 'pesertaDestroy'])->name('peserta.destroy');

    // EVENT
    Route::get('/event', [AttendanceController::class, 'eventIndex'])->name('event.index');
    Route::post('/event/simpan', [AttendanceController::class, 'eventStore'])->name('event.store');

    // 🔥 INI YANG KAMU BELUM ADA
    Route::get('/event/{id}/peserta', [AttendanceController::class, 'eventPeserta']);
    Route::post('/event/tambah-peserta', [AttendanceController::class, 'eventTambahPeserta']);

    // Scanner
    Route::get('/scanner', [AttendanceController::class, 'scanner']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Testing
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/event/{id}/peserta', [AttendanceController::class, 'eventPeserta']);
Route::post('/event/tambah-peserta', [AttendanceController::class, 'eventTambahPeserta']);
