<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AttendanceController extends Controller
{
    // Menampilkan Dashboard (Fokus Monitoring)
    public function index()
    {
        // Mengambil 10 data log terbaru beserta data user-nya
        $logs = Attendance::with('user')->orderBy('waktu_hadir', 'desc')->take(10)->get();

        // Menghitung statistik untuk kotak ringkasan di atas
        $stats = [
            'total' => Attendance::count(),
            'rfid'  => Attendance::where('metode_input', 'rfid')->count(),
            'qr'    => Attendance::where('metode_input', 'qr')->count(),
            'pending' => Attendance::where('status_verifikasi', 'pending')->count(),
        ];

        return view('dashboard', compact('logs', 'stats'));
    }

    // API untuk ESP32 atau QR Scanner

    public function reset()
{
    Attendance::truncate(); // Menghapus semua isi tabel attendance
    return redirect('/')->with('success', 'Data berhasil dikosongkan!');
}

 // API untuk ESP32 atau QR Scanner

    public function scan(Request $request)
    {
        // Mendeteksi apakah input datang dari RFID (uid) atau QR (qr)
        $id_input = $request->query('uid') ?? $request->query('qr');
        $metode = $request->has('uid') ? 'rfid' : 'qr';

        if (!$id_input) {
            return response()->json(['status' => 'error', 'message' => 'ID tidak ditemukan'], 400);
        }

        // Cari user berdasarkan salah satu ID
        $user = User::where('rfid_uid', $id_input)
                    ->orWhere('qr_data', $id_input)
                    ->first();

        if ($user) {
            // Catat ke tabel attendance dengan status pending sesuai rencana
            Attendance::create([
                'id_user' => $user->id_user,
                'id_event' => 1, 
                'metode_input' => $metode,
                'status_verifikasi' => 'pending',
                'waktu_hadir' => now()
            ]);

            return response()->json([
                'status' => 'success', 
                'nama' => $user->nama_lengkap,
                'metode' => $metode
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'User tidak terdaftar'], 404);
        // BARIS RETURN VIEW DI SINI SUDAH DIHAPUS KARENA SUDAH ADA DI ATAS
    }
}