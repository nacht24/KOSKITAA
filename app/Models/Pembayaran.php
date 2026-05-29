<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'tanggal_pembayaran',
        'bukti_pembayaran',
        'id_tagihan',
        'id_penghuni',
    ];

    // Relasi: Pembayaran melunasi satu Tagihan tertentu
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan');
    }

    // Relasi: Pembayaran dikirim oleh satu Penghuni tertentu
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni');
    }
}
