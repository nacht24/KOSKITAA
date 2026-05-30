@extends('layouts/app')

@section('title', 'Dashboard Penghuni')

@section('content')
<div class="min-h-screen bg-gray-50 flex">
    
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-6 hidden md:block">
        <div class="mb-8">
            <h1 class="text-xl font-bold text-indigo-600 tracking-wider">KOSKITAA</h1>
            <p class="text-xs text-gray-400 font-medium">Panel Anak Kos</p>
        </div>
        
        <nav class="space-y-2">
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl font-medium transition">
                <span>📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('penghuni.tagihan') }}" class="flex items-center space-x-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition">
                <span>💳</span>
                <span>Tagihan Saya</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition">
                <span>💬</span>
                <span>Komplain / Pesan</span>
            </a>
        </nav>
        
        <div class="absolute bottom-6 w-52">
            <hr class="border-gray-200 mb-4">
            <div class="flex items-center justify-between">
                <div class="truncate mr-2">
                    <p class="text-sm font-semibold text-gray-700 truncate">{{ Auth::user()->nama_penghuni ?? 'Anak Kos' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? 'penghuni@gmail.com' }}</p>
                </div>
                <a href="#" class="text-red-500 hover:text-red-700 text-sm font-medium" title="Logout">
                    🚪
                </a>
            </div>
        </div>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-200 pb-5 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->nama_penghuni ?? 'Sobat Kos' }}! 👋</h2>
                <p class="text-gray-500 text-sm mt-1">Berikut adalah status hunian dan administrasi kos Anda bulan ini.</p>
            </div>
            <div class="mt-4 md:mt-0 bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-semibold border border-indigo-100">
                📅 Periode: Mei 2026
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Nomor Kamar</span>
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-xl text-lg">🛏️</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">
                    {{ Auth::user()->kamar->no_kamar ?? 'Belum Diatur' }}
                </h3>
                <p class="text-xs text-gray-400 mt-2">Hubungi admin jika nomor kamar tidak sesuai</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Status Pembayaran</span>
                    <span class="p-2 bg-yellow-50 text-yellow-600 rounded-xl text-lg">⏳</span>
                </div>
                <h3 class="text-xl font-bold text-yellow-600">Belum Bayar</h3>
                <p class="text-xs text-gray-400 mt-4">Batas jatuh tempo: Tanggal 10 setiap bulan</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Tagihan Bulan Ini</span>
                    <span class="p-2 bg-green-50 text-green-600 rounded-xl text-lg">💰</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">Rp0</h3>
                <p class="text-xs text-indigo-600 mt-2 font-medium hover:underline cursor-pointer">
                    <a href="{{ route('penghuni.tagihan') }}">Lihat detail rincian →</a>
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h4>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('penghuni.tagihan') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium text-sm transition shadow-sm">
                    💳 Upload Bukti Bayar
                </a>
                <a href="#" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium text-sm transition">
                    🚨 Laporkan Kerusakan Kamar
                </a>
            </div>
        </div>
    </main>
</div>
@endsection