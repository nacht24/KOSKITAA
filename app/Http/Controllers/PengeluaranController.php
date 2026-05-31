<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::orderBy('created_at', 'desc')->get();
        return view('admin.pengeluaran', compact('pengeluarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nominal'           => 'required|numeric',
            'jenis_pengeluaran' => 'required|string|max:255',
            'id_admin'          => 'required|integer',
            'id_kos'            => 'required|integer',
        ]);

        Pengeluaran::create($validated);
        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id_pengeluaran)
    {
        $validated = $request->validate([
            'nominal'           => 'required|numeric',
            'jenis_pengeluaran' => 'required|string|max:255',
            'id_admin'          => 'required|integer',
            'id_kos'            => 'required|integer',
        ]);

        Pengeluaran::where('id_pengeluaran', $id_pengeluaran)->firstOrFail()->update($validated);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy($id_pengeluaran)
    {
        Pengeluaran::where('id_pengeluaran', $id_pengeluaran)->firstOrFail()->delete();

        return redirect()->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}