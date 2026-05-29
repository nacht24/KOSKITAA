<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesan';
    protected $primaryKey = 'id_pesan';

    protected $fillable = [
        'isi_pesan',
        'tanggal_pesan',
        'id_admin',
        'id_penghuni',
    ];

    // Relasi: Pesan ditujukan/diterima oleh satu Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    // Relasi: Pesan dikirim oleh satu Penghuni
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni');
    }
}
