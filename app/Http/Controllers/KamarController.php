<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Kos;

class KamarController extends Controller
{
    public function index()
    {
        $semuaKamar = Kamar::with('kos')->get();
        $daftarKos = Kos::all();
        
        return view('admin.kamar', compact('semuaKamar', 'daftarKos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kamar' => 'required|string',
            'harga_kamar' => 'required|numeric',
            'status_kamar' => 'required|in:kosong,terisi,perbaikan',
            'id_kos' => 'required|exists:kos,id_kos',
        ]);

        Kamar::create($request->only(['no_kamar', 'harga_kamar', 'status_kamar', 'id_kos']));

        return redirect()->back()->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_kamar' => 'required|string',
            'harga_kamar' => 'required|numeric',
            'status_kamar' => 'required|in:kosong,terisi,perbaikan',
            'id_kos' => 'required|exists:kos,id_kos',
        ]);

        $kamar = Kamar::findOrFail($id);
        $kamar->update($request->only(['no_kamar', 'harga_kamar', 'status_kamar', 'id_kos']));

        return redirect()->back()->with('success', 'Data kamar diperbarui.');
    }

    public function destroy($id)
    {
        $kamar = Kamar::findOrFail($id);
        $kamar->delete();

        return redirect()->back()->with('success', 'Kamar berhasil dihapus.');
    }
}