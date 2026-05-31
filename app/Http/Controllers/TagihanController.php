<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran; 
use App\Models\Tagihan;

class TagihanController extends Controller
{
    private function getTagihanAktif()
    {
        return Tagihan::where('id_penghuni', Auth::id())
            ->whereIn('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi'])
            ->first();
    }

    public function index()
    {
        $penghuni = Auth::user();
        $tagihanAktif = $this->getTagihanAktif();

        return view('penghuni.tagihan', compact('penghuni', 'tagihanAktif'));
    }

    public function showUpload($id)
    {
        $penghuni = Auth::user();
        $tagihanAktif = $this->getTagihanAktif();
        
        if ($id === 'latest') {
            $tagihan = Tagihan::where('id_penghuni', $penghuni->id_penghuni)->latest()->first();
            
            if (!$tagihan) {
                $tagihan = (object)[
                    'id_tagihan' => 0,
                    'tanggal_tagihan' => now()->format('Y-m-d'),
                    'bulan_tagihan' => now()->translatedFormat('F'),
                    'total_tagihan' => 2450000,
                ];
            }
        } else {
            $tagihan = Tagihan::findOrFail($id);
        }

        return view('penghuni.upload', compact('penghuni', 'tagihanAktif', 'tagihan'));
    }

    public function riwayat()
    {
        $penghuni = Auth::user();
        $tagihanAktif = $this->getTagihanAktif();

        $riwayatPembayaran = Pembayaran::where('id_penghuni', Auth::id())
            ->with('tagihan')
            ->latest()
            ->get();

        return view('penghuni.riwayat', compact('penghuni', 'tagihanAktif', 'riwayatPembayaran'));
    }

    public function bayarTagihan(Request $request)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'id_tagihan'     => 'required|exists:tagihan,id_tagihan',
        ]);

        if ($request->hasFile('bukti_transfer')) {
            $pathFile = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');

            Pembayaran::create([
                'id_tagihan'         => $request->id_tagihan,
                'id_penghuni'        => Auth::id(), 
                'tanggal_pembayaran' => now(), 
                'bukti_pembayaran'   => $pathFile, 
            ]);

            Tagihan::findOrFail($request->id_tagihan)->update([
                'status_pembayaran' => 'menunggu_verifikasi'
            ]);

            return redirect()->route('penghuni.tagihan')->with('success', 'Bukti transfer berhasil diunggah.');
        }

        return redirect()->back()->withErrors(['upload_error' => 'Gagal mengunggah berkas.']);
    }
}