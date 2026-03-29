<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    // Tambahkan kolom yang dibutuhkan untuk Login dan laporan ERD kamu di sini
    protected $fillable = [
        'nama_lengkap',
        'username',      // WAJIB untuk login
        'email',         // WAJIB untuk FR-09 & FR-12 (Notifikasi)
        'password',      // WAJIB untuk keamanan
        'role',          // WAJIB untuk membedakan Admin/Peserta
        'is_active',     // Untuk status akun
        'rfid_uid',
        'qr_data',
        'face_data',
        'totp_secret'    // TAMBAHKAN INI agar sesuai dengan FR-11 (Rotating QR)
    ];

    /**
     * Relasi: Satu User bisa punya banyak data kehadiran (Attendance)
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'id_user', 'id_user');
    }
}
