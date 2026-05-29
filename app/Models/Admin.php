<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Untuk autentikasi admin

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama_admin',
        'email_admin',
        'password_admin',
    ];

    protected $hidden = [
        'password_admin',
    ];

    // Relasi: Admin mengelola banyak Kos
    public function kos()
    {
        return $this->hasMany(Kos::class, 'id_admin');
    }

    // Relasi: Admin mencatat banyak Pengeluaran
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_admin');
    }
}