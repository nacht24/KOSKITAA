@extends('layouts/admin')

@section('title', 'Daftar Gedung Kos - KOSKITAA')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-8 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-xl font-black text-gray-800">Master Data Gedung Kos</h1>
                <p class="text-xs text-gray-500 mt-1">Daftarkan bangunan properti kos lo terlebih dahulu di sini</p>
            </div>
            <a href="{{ route('admin.kamar') }}" class="text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl transition">
                Lanjut Kelola Unit Kamar ➡️
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 font-bold text-sm rounded-xl">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- FORM INPUT KOS (DFD PROSES 2.3.1) -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 h-fit">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">🏢 Input Gedung Kos</h3>
                <form action="{{ route('admin.kos.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Gedung Kos</label>
                        <input type="text" name="nama_kos" required placeholder="Contoh: KOSKITAA Pakupatan" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap</label>
                        <input type="text" name="alamat_kos" required placeholder="Contoh: Jl. Raya Untirta, Serang" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 rounded-xl text-xs transition">
                        Simpan Properti Kos
                    </button>
                </form>
            </div>

            <!-- TABEL VIEW DATA KOS -->
            <div class="md:col-span-2 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">📋 Daftar Properti Terdaftar</h3>
                <table class="w-full text-left text-sm font-medium">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100">
                            <th class="pb-2">ID Kos</th>
                            <th class="pb-2">Nama Properti</th>
                            <th class="pb-2">Alamat</th>
                            <th class="pb-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($semuaKos as $kos)
                            <tr>
                                <td class="py-3 font-bold text-blue-600">#{{ $kos->id_kos }}</td>
                                <td class="py-3 text-gray-800">{{ $kos->nama_kos }}</td>
                                <td class="py-3 text-gray-500">{{ $kos->alamat_kos }}</td>
                                <td class="py-3 text-center">
                                    <form action="{{ route('admin.kos.destroy', $kos->id_kos) }}" method="POST" onsubmit="return confirm('Hapus kosan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 font-bold bg-red-50 px-2 py-1 rounded-lg hover:bg-red-100">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-xs text-gray-400">Belum ada gedung kosan didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection