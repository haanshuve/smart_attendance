<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

// ===============================
// 1. PUBLIC (TANPA LOGIN)
// ===============================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// API IoT (RFID / QR)
Route::get('/api/scan', [AttendanceController::class, 'scan']);


// ===============================
// 2. PROTECTED (WAJIB LOGIN)
// ===============================
Route::middleware(['auth'])->group(function () {

    // ---------------------------
    // DASHBOARD
    // ---------------------------
    Route::get('/', [AttendanceController::class, 'index'])->name('dashboard');
    Route::get('/reset-data', [AttendanceController::class, 'reset']);

    // ---------------------------
    // PESERTA (FR-02)
    // ---------------------------
    Route::get('/peserta', [AttendanceController::class, 'pesertaIndex'])->name('peserta.index');
    Route::post('/peserta/simpan', [AttendanceController::class, 'pesertaStore'])->name('peserta.store');
    Route::delete('/peserta/{id}', [AttendanceController::class, 'pesertaDestroy'])->name('peserta.destroy');

    // ---------------------------
    // EVENT (FR-03)
    // ---------------------------
    Route::get('/event', [AttendanceController::class, 'eventIndex'])->name('event.index');
    Route::post('/event/simpan', [AttendanceController::class, 'eventStore'])->name('event.store');

    // EDIT EVENT
    Route::get('/event/{id}/edit', [AttendanceController::class, 'eventEdit'])->name('event.edit');
    Route::post('/event/{id}/update', [AttendanceController::class, 'eventUpdate'])->name('event.update');

    // ---------------------------
    // KELOLA PESERTA DALAM EVENT
    // ---------------------------
    Route::get('/event/{id}/peserta', [AttendanceController::class, 'eventPeserta'])->name('event.peserta');
    Route::post('/event/tambah-peserta', [AttendanceController::class, 'eventTambahPeserta'])->name('event.tambahPeserta');

    // ---------------------------
    // SCANNER WEB
    // ---------------------------
    Route::get('/scanner', [AttendanceController::class, 'scanner']);

    // ---------------------------
    // LOGOUT
    // ---------------------------
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

Route::get('/api/scan', [AttendanceController::class, 'scan']);


// ===============================
// 3. TESTING
// ===============================
Route::get('/welcome', function () {
    return view('welcome');
});
