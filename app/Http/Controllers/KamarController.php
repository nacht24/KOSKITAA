<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar; 
use App\Models\Kos; // Import model Kos agar relasi di Word lo jalan

class KamarController extends Controller
{
    // 1. MENAMPILKAN DAFTAR KAMAR & DAFTAR GEDUNG KOS
    public function index()
    {
        // Ambil semua data kamar beserta relasi data kosnya agar bisa tampil di tabel
        $semuaKamar = Kamar::with('kos')->get();

        // Ambil semua data kos untuk dipasang di dropdown (select option) form tambah kamar
        $daftarKos = Kos::all(); 
        
        return view('admin.kamar', compact('semuaKamar', 'daftarKos'));
    }

    // 2. PROSES TAMBAH KAMAR BARU
    public function store(Request $request)
    {
        $request->validate([
            'no_kamar' => 'required|string',
            'harga_kamar' => 'required|numeric',
            'status_kamar' => 'required|in:kosong,terisi',
            'id_kos' => 'required|exists:kos,id_kos', // Validasi memastikan id_kos ada di tabel kos
        ]);

        // Menyimpan kamar sesuai dengan gedung kos yang dipilih bapak kos di UI web
        Kamar::create([
            'no_kamar' => $request->no_kamar,
            'harga_kamar' => $request->harga_kamar,
            'status_kamar' => $request->status_kamar,
            'id_kos' => $request->id_kos, 
        ]);

        return redirect()->back()->with('success', 'Kamar baru berhasil ditambahkan, cuy!');
    }

    // 3. PROSES UPDATE/EDIT DATA KAMAR
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_kamar' => 'required|string',
            'harga_kamar' => 'required|numeric',
            'status_kamar' => 'required|in:kosong,terisi',
            'id_kos' => 'required|exists:kos,id_kos',
        ]);

        $kamar = Kamar::findOrFail($id);
        $kamar->update([
            'no_kamar' => $request->no_kamar,
            'harga_kamar' => $request->harga_kamar,
            'status_kamar' => $request->status_kamar,
            'id_kos' => $request->id_kos,
        ]);

        return redirect()->back()->with('success', 'Data kamar berhasil diperbarui!');
    }

    // 4. PROSES HAPUS KAMAR
    public function destroy($id)
    {
        $kamar = Kamar::findOrFail($id);
        $kamar->delete();

        return redirect()->back()->with('success', 'Kamar berhasil dihapus dari sistem!');
    }
}