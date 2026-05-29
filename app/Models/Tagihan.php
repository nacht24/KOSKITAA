<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';

    protected $fillable = [
        'bulan_tagihan',
        'total_tagihan',
        'status_pembayaran',
        'id_penghuni',
    ];

    // Relasi: Tagihan dimiliki oleh satu Penghuni
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni');
    }

    // Relasi: Tagihan memiliki satu atau banyak Pembayaran (Bukti transfer)
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_tagihan');
    }
}