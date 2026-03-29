<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id_attendance';
    public $timestamps = false;

    protected $fillable = [
        'id_user', 
        'id_event', 
        'waktu_hadir', 
        'metode_input', 
        'status_verifikasi'
    ];

    // Relasi ke User (Agar dashboard bisa menampilkan nama user)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}