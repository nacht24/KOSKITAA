<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran; 
use App\Models\Tagihan; // <--- IMPORT MODEL TAGIHAN LU

class TagihanController extends Controller
{
    public function index()
    {
        $penghuni = Auth::user();

        // AMBIL TAGIHAN AKTIF: Cari tagihan milik anak kos ini yang belum lunas
        $tagihanAktif = Tagihan::where('id_penghuni', $penghuni->id_penghuni)
                               ->where('status_pembayaran', 'belum_lunas')
                               ->first();

        return view('penghuni.tagihan', compact('penghuni', 'tagihanAktif'));
    }

    // LOGIKA PROSES UPLOAD BUKTI TRANSFER RIL
    public function bayarTagihan(Request $request)
    {
        // 1. Validasi berkas upload wajib gambar maks 5MB
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'id_tagihan'     => 'required|exists:tagihan,id_tagihan', // <--- VALIDASI ID TAGIHANNYA RIL
        ]);

        // 2. Cek apakah file berhasil diunggah
        if ($request->hasFile('bukti_transfer')) {
            // Simpan file gambar ke dalam folder: storage/app/public/bukti_pembayaran
            $pathFile = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');

            // 3. Masukkan record transaksi secara dinamis
            Pembayaran::create([
                'id_tagihan'          => $request->id_tagihan,
                'id_penghuni'         => Auth::id(), 
                'tanggal_pembayaran'  => now(), 
                'bukti_pembayaran'    => $pathFile, 
            ]);

            return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah! Menunggu konfirmasi admin.');
        }

        return redirect()->back()->withErrors(['upload_error' => 'Gagal mengunggah berkas, silakan coba lagi.']);
    }
}