<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Admin;
use App\Models\Tagihan;  
use App\Models\Pengeluaran; 
use App\Models\Kos;
use App\Models\Pembayaran;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalKos = Kos::count();
        $totalKamar = Kamar::count();
        $kamarTerisi = Kamar::where('status_kamar', 'terisi')->count();
        $totalPenghuni = Penghuni::count();
        $totalPendapatan = Tagihan::where('status_pembayaran', 'lunas')->sum('total_tagihan');
        $totalPengeluaran = Pengeluaran::sum('nominal');
        $rekapKeuangan = $totalPendapatan - $totalPengeluaran;

        $okupansi = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;

        $transaksiTerbaru = Pembayaran::with(['penghuni', 'tagihan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $tahunSekarang = date('Y');
        $listBulan = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun'];
        $dataTren = [];

        foreach ($listBulan as $angkaBulan => $namaBulan) {
            $totalBulanIni = Tagihan::where('status_pembayaran', 'lunas')
                ->whereYear('created_at', $tahunSekarang)
                ->whereMonth('created_at', $angkaBulan)
                ->sum('total_tagihan');
                
            $dataTren[$namaBulan] = $totalBulanIni;
        }

        $pendapatanMaksimal = max($dataTren) > 0 ? max($dataTren) : 1;

        return view('admin.dashboard', compact(
            'totalKos',
            'totalKamar',
            'kamarTerisi',
            'totalPenghuni', 
            'totalPendapatan',
            'totalPengeluaran',
            'rekapKeuangan',
            'okupansi',
            'transaksiTerbaru',
            'dataTren',
            'pendapatanMaksimal'
        ));
    }
}