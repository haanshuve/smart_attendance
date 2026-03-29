<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Ditambahkan untuk query tabel events

class AttendanceController extends Controller
{
    // --- 1. DASHBOARD & MONITORING ---

    public function index()
    {
        $logs = Attendance::with('user')->orderBy('waktu_hadir', 'desc')->take(10)->get();

        $stats = [
            'total'   => Attendance::count(),
            'rfid'    => Attendance::where('metode_input', 'rfid')->count(),
            'qr'      => Attendance::where('metode_input', 'qr')->count(),
            'pending' => Attendance::where('status_verifikasi', 'pending')->count(),
        ];

        return view('dashboard', compact('logs', 'stats'));
    }

    public function reset()
    {
        Attendance::truncate();
        return redirect('/')->with('success', 'Data log berhasil dikosongkan!');
    }


    // --- 2. API UNTUK IOT (ESP32 / QR SCANNER) ---

    public function scan(Request $request)
    {
        $id_input = $request->query('uid') ?? $request->query('qr');
        $metode = $request->has('uid') ? 'rfid' : 'qr';

        if (!$id_input) {
            return response()->json(['status' => 'error', 'message' => 'ID tidak ditemukan'], 400);
        }

        $user = User::where('rfid_uid', $id_input)
                    ->orWhere('qr_data', $id_input)
                    ->first();

        if ($user) {
            Attendance::create([
                'id_user' => $user->id_user,
                'id_event' => 1, // Default ke event pertama, bisa dikembangkan nanti
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
    }


    // --- 3. FITUR FR-02: MANAJEMEN DATA PESERTA ---

    public function pesertaIndex()
    {
        $users = User::where('role', 'peserta')->orderBy('id_user', 'desc')->get();
        return view('admin.peserta.index', compact('users'));
    }

    public function pesertaStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'username'     => 'required|unique:users,username',
            'email'        => 'required|email|unique:users,email',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make('peserta123'),
            'rfid_uid'     => $request->rfid_uid,
            'role'         => 'peserta',
            'is_active'    => 1
        ]);

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function pesertaDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Peserta berhasil dihapus!');
    }


    // --- 4. FITUR FR-03: MANAJEMEN ACARA (Disesuaikan image_e291c7.jpg) ---

    public function eventIndex()
    {
        // Mengambil semua data dari tabel events
        $events = DB::table('events')->orderBy('id_event', 'desc')->get();
        return view('admin.event.index', compact('events'));
    }

    public function eventStore(Request $request)
    {
        // Validasi sesuai kolom di database kamu
        $request->validate([
            'nama_event'   => 'required',
            'tanggal'      => 'required|date',
            'lokasi'       => 'required',
            'status_event' => 'required'
        ]);

        DB::table('events')->insert([
            'nama_event'   => $request->nama_event,
            'tanggal'      => $request->tanggal,
            'lokasi'       => $request->lokasi,
            'deskripsi'    => $request->deskripsi,
            'status_event' => $request->status_event,
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        return redirect()->route('event.index')->with('success', 'Acara berhasil dibuat!');
    }
}
