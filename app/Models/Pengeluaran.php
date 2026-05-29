<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'nominal',
        'jenis_pengeluaran',
        'id_admin',
        'id_kos',
    ];

    // Relasi: Pengeluaran dicatat oleh satu Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    // Relasi: Pengeluaran dibebankan pada satu Kos
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }
}
