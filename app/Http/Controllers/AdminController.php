<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Admin;
use App\Models\Tagihan;  
use App\Models\Pengeluaran; 
use App\Models\Kos;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalKos = Kos::count();
        $totalKamar = Kamar::count();
        $totalPenghuni = Penghuni::count();
        $totalPendapatan = Tagihan::where('status_pembayaran', 'lunas')->sum('total_tagihan');
        $totalPengeluaran = Pengeluaran::sum('nominal');
        $rekapKeuangan = $totalPendapatan - $totalPengeluaran;

        return view('admin.dashboard', compact(
            'totalKos',
            'totalKamar', 
            'totalPenghuni', 
            'totalPendapatan',
            'totalPengeluaran',
            'rekapKeuangan'
        ));
    }
}