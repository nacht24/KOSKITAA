<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function indexAdmin()
    {
        $semuaPembayaran = Pembayaran::with(['penghuni', 'tagihan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $menungguTinjauan = Pembayaran::whereHas('tagihan', function ($query) {
            $query->where('status_pembayaran', 'menunggu_verifikasi');
        })->count();

        $disetujuiHariIni = Pembayaran::whereHas('tagihan', function ($query) {
            $query->where('status_pembayaran', 'lunas')
                  ->whereDate('tagihan.updated_at', Carbon::today());
        })->count();

        $totalMemverifikasi = Pembayaran::whereHas('tagihan', function ($query) {
            $query->where('status_pembayaran', 'menunggu_verifikasi');
        })->get()->sum(function ($bayar) {
            return $bayar->tagihan->total_tagihan ?? 0;
        });

        return view('admin.pembayaran', compact(
            'semuaPembayaran',
            'menungguTinjauan',
            'disetujuiHariIni',
            'totalMemverifikasi'
        ));
    }

    public function setujuiPembayaran(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        $tagihan = Tagihan::findOrFail($pembayaran->id_tagihan);
        $tagihan->update([
            'status_pembayaran' => 'lunas'
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil disetujui.');
    }

    public function tolakPembayaran(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        $tagihan = Tagihan::findOrFail($pembayaran->id_tagihan);
        $tagihan->update([
            'status_pembayaran' => 'belum_bayar'
        ]);

        $pembayaran->delete();

        return redirect()->back()->with('success', 'Bukti pembayaran ditolak.');
    }
}