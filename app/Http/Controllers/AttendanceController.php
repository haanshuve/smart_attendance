<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // ===============================
    // 1. DASHBOARD
    // ===============================
    public function index()
    {
        $logs = Attendance::with('user')
            ->orderBy('waktu_hadir', 'desc')
            ->take(10)
            ->get();

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

    // ===============================
    // 2. API SCAN (FIXED 🔥)
    // ===============================
    public function scan(Request $request)
    {
        $qr = $request->query('qr');

        if (!$qr) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR kosong'
            ]);
        }

        // 🔎 cari user dari QR
        $user = DB::table('users')
            ->where('qr_data', $qr)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak terdaftar'
            ]);
        }

        // 🔥 ambil event aktif
        $event = DB::table('events')
            ->where('is_active', 1)
            ->first();

        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada event aktif'
            ]);
        }

        // 🔥 cek invitation
        $invitation = DB::table('invitations')
            ->where('user_id', $user->id_user)
            ->where('event_id', $event->id_event)
            ->first();

        if (!$invitation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak terdaftar di event'
            ]);
        }

        // 🔥 cek double scan
        $already = Attendance::where('id_user', $user->id_user)
            ->where('id_event', $event->id_event)
            ->exists();

        if ($already) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sudah melakukan absensi'
            ]);
        }

        // 🔥 simpan attendance
        Attendance::create([
            'id_user' => $user->id_user,
            'id_event' => $event->id_event,
            'metode_input' => 'qr',
            'status_verifikasi' => 'verified',
            'waktu_hadir' => now()
        ]);

        // 🔥 update invitation
        DB::table('invitations')
            ->where('id', $invitation->id)
            ->update([
                'status' => 'hadir'
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil: ' . $user->nama_lengkap
        ]);
    }

    // ===============================
    // 3. PESERTA
    // ===============================
    public function pesertaIndex()
    {
        $users = User::where('role', 'peserta')
            ->orderBy('id_user', 'desc')
            ->get();

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

        return redirect()->route('peserta.index')
            ->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function pesertaDestroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Peserta berhasil dihapus!');
    }

    // ===============================
    // 4. EVENT
    // ===============================
    public function eventIndex()
    {
        $events = DB::table('events')
            ->orderBy('id_event', 'desc')
            ->get();

        return view('admin.event.index', compact('events'));
    }

    public function eventStore(Request $request)
    {
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
            'is_active'    => 1
        ]);

        return redirect()->route('event.index')
            ->with('success', 'Acara berhasil dibuat!');
    }

    public function eventEdit($id)
    {
        $event = DB::table('events')->where('id_event', $id)->first();
        return view('admin.event.edit', compact('event'));
    }

    public function eventUpdate(Request $request, $id)
    {
        DB::table('events')->where('id_event', $id)->update([
            'nama_event'   => $request->nama_event,
            'tanggal'      => $request->tanggal,
            'lokasi'       => $request->lokasi,
            'deskripsi'    => $request->deskripsi,
            'status_event' => $request->status_event,
        ]);

        return redirect()->route('event.index')->with('success', 'Event berhasil diupdate!');
    }

    // ===============================
    // 5. SCANNER VIEW
    // ===============================
    public function scanner()
    {
        return view('scanner');
    }
}
