@extends('layouts/app')

@section('title', 'Tagihan Saya')

@section('content')
<div class="min-h-screen bg-gray-50 flex">
    
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-6 hidden md:block">
        <div class="mb-8">
            <h1 class="text-xl font-bold text-indigo-600 tracking-wider">KOSKITAA</h1>
            <p class="text-xs text-gray-400 font-medium">Panel Anak Kos</p>
        </div>
        
        <nav class="space-y-2">
            <a href="{{ route('penghuni.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition">
                <span>📊</span>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl font-medium transition">
                <span>💳</span>
                <span>Tagihan Saya</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition">
                <span>💬</span>
                <span>Komplain / Pesan</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        <header class="border-b border-gray-200 pb-5 mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Rincian Tagihan Bulanan</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau riwayat pembayaran dan tagihan aktif kos Anda di sini.</p>
        </header>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl text-sm font-semibold flex items-center space-x-2">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl text-sm font-semibold">
                <p class="font-bold mb-1">❌ Unggah Bukti Gagal:</p>
                <ul class="list-disc list-inside text-xs font-normal">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Tagihan Anda</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 text-sm font-semibold text-gray-500">
                                <th class="pb-3">Bulan</th>
                                <th class="pb-3">Jumlah</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                            @if($tagihanAktif)
                                <tr>
                                    <td class="py-4 font-bold text-gray-800">{{ $tagihanAktif->bulan_tagihan }} {{ \Carbon\Carbon::parse($tagihanAktif->tanggal_tagihan)->format('Y') }}</td>
                                    <td class="py-4 font-black text-indigo-600">Rp {{ number_format($tagihanAktif->total_tagihan, 0, ',', '.') }}</td>
                                    <td class="py-4">
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-bold uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $tagihanAktif->status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <button onclick="document.getElementById('modal-bayar').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transition">
                                            Bayar Sekarang →
                                        </button>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-green-600 font-bold text-sm bg-green-50/50 rounded-xl">
                                        🎉 Mantap! Semua tagihan kos lo bulan ini udah lunas, cuy.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Rekening Kos</h3>
                <p class="text-sm text-gray-600 mb-4">Silakan transfer nominal tagihan Anda ke salah satu rekening resmi pemilik kos berikut:</p>
                
                <div class="space-y-3">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs font-bold text-gray-400">BANK CENTRAL ASIA (BCA)</p>
                        <p class="text-base font-bold text-gray-800 mt-1">8420-XXXX-XXXX</p>
                        <p class="text-xs text-gray-500">a.n. Pemilik KosKitaa</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs font-bold text-gray-400">E-WALLET (GOPAY/OVO)</p>
                        <p class="text-base font-bold text-gray-800 mt-1">0812-XXXX-XXXX</p>
                        <p class="text-xs text-gray-500">KosKitaa Digital Access</p>
                    </div>
                </div>
            </div>
        </div>

        @if($tagihanAktif)
            <div id="modal-bayar" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden p-4">
                <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl animate-fade-in">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-bold text-gray-800">Konfirmasi Pembayaran</h4>
                        <button onclick="document.getElementById('modal-bayar').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                    </div>
                    
                    <form action="{{ route('penghuni.tagihan.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="id_tagihan" value="{{ $tagihanAktif->id_tagihan }}">

                        <div class="mb-4 bg-indigo-50/50 p-3 rounded-xl border border-indigo-100 text-xs font-medium text-indigo-800 space-y-1">
                            <p>📌 Tagihan: <span class="font-bold">{{ $tagihanAktif->bulan_tagihan }}</span></p>
                            <p>💰 Nominal: <span class="font-bold">Rp {{ number_format($tagihanAktif->total_tagihan, 0, ',', '.') }}</span></p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Transfer (.png / .jpg)</label>
                            <input type="file" name="bukti_transfer" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Contoh: Pembayaran sdh dikirim via BCA m-banking"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </main>
</div>
@endsection