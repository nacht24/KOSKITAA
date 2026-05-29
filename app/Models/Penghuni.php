<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Untuk autentikasi penyewa

class Penghuni extends Authenticatable
{
    use HasFactory;

    protected $table = 'penghuni';
    protected $primaryKey = 'id_penghuni';

    protected $fillable = [
        'nama_penghuni',
        'email',
        'password',
        'id_kamar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi: Penghuni menempati satu Kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    // Relasi: Penghuni memiliki banyak Tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_penghuni');
    }

    // Relasi: Penghuni mengirim banyak Pesan/Laporan
    public function pesan()
    {
        return $this->hasMany(Pesan::class, 'id_penghuni');
    }
}