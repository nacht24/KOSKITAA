@extends('layouts.admin')

@section('title', 'Daftar Gedung Kos - KOSKITAA')

@section('content')
<div class="p-8 space-y-8 max-w-6xl mx-auto">
    
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Master Data Properti</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Daftarkan dan pantau seluruh aset bangunan fisik kos lo di sini.</p>
        </div>
        <a href="{{ route('admin.kamar') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition shadow-lg shadow-blue-100 flex items-center gap-2">
            Lanjut Kelola Unit Kamar <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-xl shadow-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 h-32 justify-between flex-col">
            <div class="flex justify-between items-start w-full">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Gedung Kos</p>
                    <h2 class="text-3xl font-black text-gray-900 mt-1">{{ $semuaKos->count() }}</h2>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 h-32 justify-between flex-col">
            <div class="flex justify-between items-start w-full">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lokasi Aktif</p>
                    <h2 class="text-3xl font-black text-gray-900 mt-1">
                        {{ $semuaKos->unique('alamat_kos')->count() }}
                    </h2>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
            <div>
                <h3 class="text-sm font-black text-gray-900">Input Properti Baru</h3>
                <p class="text-2xs text-gray-400 font-medium mt-0.5">Asset Management</p>
            </div>
            <hr class="border-gray-50">
            
            <form action="{{ route('admin.kos.store') }}" method="POST" class="space-y-4 pt-2">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Nama Gedung</label>
                    <input type="text" name="nama_kos" required placeholder="Contoh: KOSKITAA Pakupatan" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium transition">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Alamat Lengkap</label>
                    <input type="text" name="alamat_kos" required placeholder="Contoh: Jl. Raya Untirta, Serang" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium transition">
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl text-xs transition shadow-sm">
                    Simpan Properti Aset
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-gray-50/20">
                <h3 class="text-base font-black text-gray-900">Daftar Bangunan Properti</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 bg-gray-50/50">
                            <th class="p-4 pl-6">ID Gedung</th>
                            <th class="p-4">Nama Properti</th>
                            <th class="p-4">Alamat Lokasi</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm font-medium text-gray-700">
                        @forelse($semuaKos as $kos)
                            <tr class="hover:bg-gray-50/40 transition">
                                <td class="p-4 pl-6 font-bold text-blue-600 text-xs">#KS-0{{ $kos->id_kos }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-50 text-blue-600 font-bold text-2xs rounded-full flex items-center justify-center border border-blue-100/30">
                                            🏢
                                        </div>
                                        <span class="font-bold text-gray-900 text-sm leading-tight">{{ $kos->nama_kos }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-gray-500 text-xs font-medium">{{ $kos->alamat_kos }}</td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center">
                                        <form action="{{ route('admin.kos.destroy', $kos->id_kos) }}" method="POST" onsubmit="return confirm('Hapus gedung properti beserta seluruh kamar didalamnya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-gray-400 text-xs font-medium">Belum ada gedung properti kos yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection