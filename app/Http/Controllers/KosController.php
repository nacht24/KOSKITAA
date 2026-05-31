<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;

class KosController extends Controller
{
    public function index()
    {
        $semuaKos = Kos::all();
        return view('admin.kos', compact('semuaKos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kos' => 'required|string|max:255',
            'alamat_kos' => 'required|string',
        ]);

        Kos::create([
            'nama_kos' => $request->nama_kos,
            'alamat_kos' => $request->alamat_kos,
            'id_admin' => 1,
        ]);

        return redirect()->back()->with('success', 'Gedung kos berhasil didaftarkan.');
    }

    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);
        $kos->delete();

        return redirect()->back()->with('success', 'Gedung kos berhasil dihapus.');
    }
}