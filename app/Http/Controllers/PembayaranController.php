<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function indexAdmin()
    {
        // 1. Ambil semua data pembayaran untuk tabel riwayat (beserta relasinya agar efisien)
        $semuaPembayaran = Pembayaran::with(['penghuni', 'tagihan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // 2. Hitung jumlah pembayaran yang Menunggu Tinjauan
        // Menghitung baris pembayaran yang tagihannya memiliki status selain 'lunas' (atau sesuai logic web lu)
        $menungguTinjauan = Pembayaran::whereHas('tagihan', function ($query) {
            $query->where('status_pembayaran', '!=', 'lunas');
        })->count();

        // 3. Hitung jumlah pembayaran yang Disetujui Hari Ini
        // Menghitung pembayaran yang status tagihannya sudah 'lunas' dan di-update hari ini
        $disetujuiHariIni = Pembayaran::whereHas('tagihan', function ($query) {
            $query->where('status_pembayaran', 'lunas')
                  ->whereDate('updated_at', Carbon::today());
        })->count();

        // 4. Hitung total uang yang sedang diverifikasi (menunggu tinjauan)
        // Menjumlahkan total_tagihan dari tagihan-tagihan yang statusnya belum lunas
        $totalMemverifikasi = Pembayaran::whereHas('tagihan', function ($query) {
            $query->where('status_pembayaran', '!=', 'lunas');
        })->get()->sum(function ($bayar) {
            return $bayar->tagihan->total_tagihan ?? 0;
        });

        // 5. Kirim semua data ke view
        return view('admin.pembayaran', compact(
            'semuaPembayaran',
            'menungguTinjauan',
            'disetujuiHariIni',
            'totalMemverifikasi'
        ));
    }
}