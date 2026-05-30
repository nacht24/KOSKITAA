<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    public $incrementing = true;
    
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'nominal',
        'jenis_pengeluaran',
        'id_admin',
        'id_kos'
    ];
}