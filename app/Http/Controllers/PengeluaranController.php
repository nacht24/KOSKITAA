<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::all();
        return view('admin.pengeluaran', compact('pengeluarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal'           => 'required|numeric',
            'jenis_pengeluaran' => 'required|string|max:255',
            'id_admin'          => 'required|integer',
            'id_kos'            => 'required|integer',
        ]);

        Pengeluaran::create($request->all());
        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id_pengeluaran)
    {
        $request->validate([
            'nominal'           => 'required|numeric',
            'jenis_pengeluaran' => 'required|string|max:255',
            'id_admin'          => 'required|integer',
            'id_kos'            => 'required|integer',
        ]);

        $pengeluaran = Pengeluaran::where('id_pengeluaran', $id_pengeluaran)->firstOrFail();
        $pengeluaran->update($request->all());

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id_pengeluaran)
    {
        $pengeluaran = Pengeluaran::where('id_pengeluaran', $id_pengeluaran)->firstOrFail();
        $pengeluaran->delete();

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data berhasil dihapus.');
    }
}