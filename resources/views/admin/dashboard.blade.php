@extends('layouts.admin')

@section('title', 'Dashboard - KOSKITAA')

@section('content')
<div class="p-8 space-y-8 max-w-6xl mx-auto">
    
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Ringkasan Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Selamat datang kembali, inilah yang terjadi di Skyline Management hari ini.</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition shadow-lg shadow-blue-100">
            + Tambah Transaksi Baru
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kamar Aktif</p>
                    <h2 class="text-3xl font-black text-gray-900 mt-2">{{ $totalKamar ?? 45 }} <span class="text-lg font-bold text-gray-400">/ 50</span></h2>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">90% Okupansi</span>
            </div>
            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full" style="width: 90%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 h-40">
            <div class="p-3 bg-gray-50 rounded-xl text-xl">👥</div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Penyewa</p>
                <h2 class="text-3xl font-black text-gray-900 mt-2">{{ $totalPenghuni ?? 45 }}</h2>
                <p class="text-xs text-gray-400 mt-2 font-medium">Di seluruh properti yang dikelola</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800">Tren Pendapatan</h3>
            <select class="text-xs font-bold text-gray-500 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:ring-0">
                <option>6 Bulan Terakhir</option>
            </select>
        </div>
        <div class="flex items-end justify-between h-48 pt-4 px-4 border-b border-gray-100">
            <div class="w-16 bg-blue-100 h-20 rounded-t-xl flex flex-col justify-end items-center"><span class="text-2xs font-bold text-gray-400 mb-[-24px] pb-6">Jan</span></div>
            <div class="w-16 bg-blue-100 h-28 rounded-t-xl flex flex-col justify-end items-center"><span class="text-2xs font-bold text-gray-400 mb-[-24px] pb-6">Feb</span></div>
            <div class="w-16 bg-blue-100 h-24 rounded-t-xl flex flex-col justify-end items-center"><span class="text-2xs font-bold text-gray-400 mb-[-24px] pb-6">Mar</span></div>
            <div class="w-16 bg-blue-100 h-32 rounded-t-xl flex flex-col justify-end items-center"><span class="text-2xs font-bold text-gray-400 mb-[-24px] pb-6">Apr</span></div>
            <div class="w-16 bg-blue-100 h-36 rounded-t-xl flex flex-col justify-end items-center"><span class="text-2xs font-bold text-gray-400 mb-[-24px] pb-6">Mei</span></div>
            <div class="w-16 bg-blue-600 h-44 rounded-t-xl flex flex-col justify-end items-center"><span class="text-2xs font-bold text-gray-400 mb-[-24px] pb-6">Jun</span></div>
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
                    <tr>
                        <td class="p-4 pl-6 text-gray-500 text-xs">24 Okt 2023</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-50 text-blue-600 font-bold text-xs rounded-full flex items-center justify-center">BK</div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Budi Kusuma</p>
                                    <p class="text-xs text-gray-400 font-medium">Kamar 302</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-bold text-gray-900">Rp 3.500.000</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full">● Berhasil</span>
                        </td>
                        <td class="p-4 text-center text-gray-400 cursor-pointer hover:text-gray-900">⋮</td>
                    </tr>
                    <tr>
                        <td class="p-4 pl-6 text-gray-500 text-xs">24 Okt 2023</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-50 text-purple-600 font-bold text-xs rounded-full flex items-center justify-center">SL</div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Siti Lestari</p>
                                    <p class="text-xs text-gray-400 font-medium">Kamar 105</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-bold text-gray-900">Rp 2.800.000</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full">● Tertunda</span>
                        </td>
                        <td class="p-4 text-center text-gray-400 cursor-pointer hover:text-gray-900">⋮</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-4 text-center border-t border-gray-50 bg-gray-50/30">
            <a href="#" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Transaksi ➡️</a>
        </div>
    </div>

</div>
@endsection