<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    protected $table = 'kos';
    protected $primaryKey = 'id_kos';

    protected $fillable = [
        'nama_kos',
        'alamat_kos',
        'id_admin',
    ];

    // Relasi: Satu Kos menyediakan Banyak Kamar (1:M di ERD Word lo)
    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_kos');
    }
}