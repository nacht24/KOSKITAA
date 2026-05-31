<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'no_kamar',
        'harga_kamar',
        'status_kamar',
        'id_kos',
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    public function penghuni()
    {
        return $this->hasMany(Penghuni::class, 'id_kamar');
    }
}