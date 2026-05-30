<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;

class KosController extends Controller
{
    // 1. TAMPILKAN HALAMAN DAFTAR GEDUNG KOS (DFD PROSES 2.3.5)
    public function index()
    {
        $semuaKos = Kos::all();
        return view('admin.kos', compact('semuaKos'));
    }

    // 2. PROSES TAMBAH GEDUNG KOS BARU (DFD PROSES 2.3.1)
    public function store(Request $request)
    {
        $request->validate([
            'nama_kos' => 'required|string|max:255',
            'alamat_kos' => 'required|string',
        ]);

        Kos::create([
            'nama_kos' => $request->nama_kos,
            'alamat_kos' => $request->alamat_kos,
            'id_admin' => 1, // Default ID bapak kos yang login
        ]);

        return redirect()->back()->with('success', 'Gedung Kos baru berhasil didaftarkan, cuy!');
    }

    // 3. PROSES HAPUS GEDUNG KOS
    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);
        $kos->delete();

        return redirect()->back()->with('success', 'Gedung Kos berhasil dihapus dari sistem!');
    }
}