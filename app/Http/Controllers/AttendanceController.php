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
    // 2. API SCAN (ESP32 / QR)
    // ===============================
    public function scan(Request $request)
    {
        $id_input = $request->query('uid') ?? $request->query('qr');
        $metode = $request->has('uid') ? 'rfid' : 'qr';

        if (!$id_input) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID tidak ditemukan'
            ], 400);
        }

        // 🔍 CARI USER
        $user = User::where('rfid_uid', $id_input)
                    ->orWhere('qr_data', $id_input)
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak terdaftar'
            ], 404);
        }

        // 🔥 AMBIL EVENT AKTIF
        $event = DB::table('events')->where('is_active', 1)->first();

        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada event aktif'
            ], 400);
        }

        // 🔥 CEK APAKAH USER TERDAFTAR DI EVENT
        $invitation = DB::table('invitations')
            ->where('user_id', $user->id_user)
            ->where('event_id', $event->id_event)
            ->first();

        if (!$invitation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak terdaftar di event'
            ], 403);
        }

        // 🔥 CEK DOUBLE SCAN
        $already = Attendance::where('id_user', $user->id_user)
            ->where('id_event', $event->id_event)
            ->exists();

        if ($already) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sudah melakukan absensi'
            ], 409);
        }

        // 🔥 INSERT ATTENDANCE
        Attendance::create([
            'id_user' => $user->id_user,
            'id_event' => $event->id_event,
            'metode_input' => $metode,
            'status_verifikasi' => 'verified',
            'waktu_hadir' => now()
        ]);

        // 🔥 UPDATE STATUS INVITATION
        DB::table('invitations')
            ->where('id', $invitation->id)
            ->update([
                'status' => 'hadir'
            ]);

        return response()->json([
            'status' => 'success',
            'nama' => $user->nama_lengkap,
            'event' => $event->nama_event,
            'metode' => $metode
        ]);
    }

    // ===============================
    // 3. MANAJEMEN PESERTA
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
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Peserta berhasil dihapus!');
    }

    // ===============================
    // 4. MANAJEMEN EVENT
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
        'is_active'    => 0
    ]);

    return redirect()->route('event.index')
        ->with('success', 'Acara berhasil dibuat!');
}

    // ===============================
    // 5. HALAMAN PESERTA EVENT
    // ===============================
    public function eventPeserta($id)
    {
        $event = DB::table('events')->where('id_event', $id)->first();

        $participants = DB::table('invitations')
            ->join('users', 'users.id_user', '=', 'invitations.user_id')
            ->where('invitations.event_id', $id)
            ->select(
                'users.nama_lengkap',
                'users.email',
                'invitations.method',
                'invitations.status'
            )
            ->get();

        $users = User::where('role', 'peserta')->get();

        return view('admin.event.peserta', compact('event', 'participants', 'users'));
    }

    // ===============================
    // 6. TAMBAH PESERTA KE EVENT
    // ===============================
    public function eventTambahPeserta(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'event_id' => 'required',
            'method' => 'required|in:rfid,qr'
        ]);

        DB::table('invitations')->insert([
            'user_id' => $request->user_id,
            'event_id' => $request->event_id,
            'method' => $request->input('method'),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Peserta berhasil ditambahkan ke event!');
    }

public function eventUpdate(Request $request, $id)
{
    $request->validate([
        'nama_event'   => 'required',
        'tanggal'      => 'required|date',
        'lokasi'       => 'required',
        'status_event' => 'required'
    ]);

    DB::table('events')->where('id_event', $id)->update([
        'nama_event'   => $request->nama_event,
        'tanggal'      => $request->tanggal,
        'lokasi'       => $request->lokasi,
        'deskripsi'    => $request->deskripsi,
        'status_event' => $request->status_event,
    ]);

    return redirect()->route('event.index')->with('success', 'Event berhasil diupdate!');
}

    public function eventEdit($id)
{
    $event = DB::table('events')->where('id_event', $id)->first();
    return view('admin.event.edit', compact('event'));
}
}
