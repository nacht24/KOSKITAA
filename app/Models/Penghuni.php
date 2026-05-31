<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'no_hp',
        'durasi_sewa',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_penghuni');
    }

    public function pesan()
    {
        return $this->hasMany(Pesan::class, 'id_penghuni');
    }
}