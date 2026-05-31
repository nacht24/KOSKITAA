<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Tagihan;
use App\Models\Pengeluaran;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->get('bulan', 6);
        $dari = Carbon::now()->subMonths($bulan - 1)->startOfMonth();
        $sampai = Carbon::now()->endOfMonth();

        $totalPemasukan = Tagihan::where('status_pembayaran', 'Lunas')
            ->whereBetween('updated_at', [$dari, $sampai])
            ->sum('total_tagihan');

        $totalPengeluaran = Pengeluaran::whereBetween('created_at', [$dari, $sampai])
            ->sum('nominal');

        $totalBersih = $totalPemasukan - $totalPengeluaran;

        $pemasukanPerBulan = Tagihan::select(
                DB::raw('MONTH(updated_at) as bulan'),
                DB::raw('YEAR(updated_at) as tahun'),
                DB::raw('SUM(total_tagihan) as total')
            )
            ->where('status_pembayaran', 'Lunas')
            ->whereBetween('updated_at', [$dari, $sampai])
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . str_pad($r->bulan, 2, '0', STR_PAD_LEFT));

        $pengeluaranPerBulan = Pengeluaran::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('SUM(nominal) as total')
            )
            ->whereBetween('created_at', [$dari, $sampai])
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . str_pad($r->bulan, 2, '0', STR_PAD_LEFT));

        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        for ($i = $bulan - 1; $i >= 0; $i--) {
            $bulanIni = Carbon::now()->subMonths($i);
            $key = $bulanIni->format('Y-m');
            
            $chartLabels[] = $bulanIni->translatedFormat('M');
            $chartPemasukan[] = (int) ($pemasukanPerBulan[$key]->total ?? 0);
            $chartPengeluaran[] = (int) ($pengeluaranPerBulan[$key]->total ?? 0);
        }

        $detailPengeluaran = Pengeluaran::whereBetween('created_at', [$dari, $sampai])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.laporan', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'totalBersih',
            'chartLabels',
            'chartPemasukan',
            'chartPengeluaran',
            'detailPengeluaran',
            'bulan'
        ));
    }
}