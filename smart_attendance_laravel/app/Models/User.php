<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; 
    protected $primaryKey = 'id_user'; 
    public $timestamps = false; 

    protected $fillable = [
        'nama_lengkap', 
        'rfid_uid', 
        'qr_data', 
        'face_data', 
        'role'
    ];

    /**
     * Relasi: Satu User bisa punya banyak data kehadiran (Attendance)
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'id_user', 'id_user');
    }
}