@extends('layouts.admin')

@section('title', 'Dashboard - KOSKITAA')

@section('content')
<div class="p-8 space-y-8 max-w-6xl mx-auto">
    
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Ringkasan Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Selamat datang kembali, inilah yang terjadi di Residential Management hari ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kamar Aktif</p>
                    <h2 class="text-3xl font-black text-gray-900 mt-2">
                        {{ $kamarTerisi }} <span class="text-lg font-bold text-gray-400">/ {{ $totalKamar }}</span>
                    </h2>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">
                    {{ $okupansi }}% Okupansi
                </span>
            </div>
            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $okupansi }}%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 h-40">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl font-bold w-12 h-12 flex items-center justify-center">👥</div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Penyewa</p>
                <h2 class="text-3xl font-black text-gray-900 mt-2">{{ $totalPenghuni }}</h2>
                <p class="text-xs text-gray-400 mt-2 font-medium">Di seluruh properti yang dikelola</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800">Tren Pendapatan</h3>
            <select class="text-xs font-bold text-gray-500 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:ring-0 cursor-pointer">
                <option>6 Bulan Terakhir</option>
            </select>
        </div>
        <div class="flex items-end justify-between h-48 pt-4 px-4 border-b border-gray-100">
            @foreach($dataTren as $bulan => $totalUang)
                @php
                    $tinggiBatang = ($totalUang / $pendapatanMaksimal) * 100;
                    $tinggiBar = $totalUang > 0 ? max($tinggiBatang, 8) : 4; 
                @endphp
                
                <div class="w-16 flex flex-col justify-end items-center group relative">
                    <div class="absolute bottom-full mb-2 bg-gray-900 text-white text-3xs font-bold px-2 py-1 rounded shadow-md opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none whitespace-nowrap z-10">
                        Rp {{ number_format($totalUang, 0, ',', '.') }}
                    </div>

                    <div class="w-full {{ $bulan == date('M') || ($bulan == 'Mei' && date('M') == 'May') ? 'bg-blue-600' : 'bg-blue-100' }} rounded-t-xl transition-all duration-500" 
                         style="height: {{ $tinggiBar }}px;">
                    </div>
                    
                    <span class="text-2xs font-bold {{ $bulan == date('M') || ($bulan == 'Mei' && date('M') == 'May') ? 'text-blue-600 font-black' : 'text-gray-400' }} mt-2 pt-1">
                        {{ $bulan }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50">
            <h3 class="font-bold text-gray-800">Transaksi Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 bg-gray-50/50">
                        <th class="p-4 pl-6">Tanggal</th>
                        <th class="p-4">Penyewa</th>
                        <th class="p-4">Jumlah</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-medium text-gray-700">
                    @forelse($transaksiTerbaru as $t)
                        @php
                            $status = $t->tagihan->status_pembayaran ?? 'belum_bayar';
                            $namaPenyewa = $t->penghuni->nama_penghuni ?? 'Anak Kos';
                            $initials = strtoupper(substr($namaPenyewa, 0, 2));
                        @endphp
                        <tr class="hover:bg-gray-50/40 transition">
                            <td class="p-4 pl-6 text-gray-500 text-xs">
                                {{ $t->created_at ? $t->created_at->format('d M Y') : now()->format('d M Y') }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 text-blue-600 font-bold text-xs rounded-full flex items-center justify-center uppercase">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $namaPenyewa }}</p>
                                        <p class="text-xs text-gray-400 font-medium">
                                            Kamar {{ $t->penghuni->kamar->no_kamar ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 font-bold text-gray-900">
                                Rp {{ number_format($t->tagihan->total_tagihan ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                @if($status === 'lunas')
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full">● Berhasil</span>
                                @elseif($status === 'menunggu_verifikasi')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full">● Tertunda</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-50 text-gray-500 text-xs font-bold rounded-full">● Belum Bayar</span>
                                @endif
                            </td>
                            <td class="p-4 text-center text-gray-400 cursor-pointer hover:text-gray-900 font-bold">⋮</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 text-xs">Belum ada riwayat transaksi pembayaran masuk rill.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 text-center border-t border-gray-50 bg-gray-50/30">
            <a href="{{ route('admin.pembayaran') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Transaksi ➡️</a>
        </div>
    </div>

</div>
@endsection