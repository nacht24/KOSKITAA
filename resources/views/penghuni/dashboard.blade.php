@extends('layouts.app')

@section('title', 'Dashboard Penghuni')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            Halo, {{ explode(' ', Auth::user()->nama_penghuni ?? 'Sobat')[0] }} 👋
        </h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Selamat datang kembali di dashboard penyewa Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-between min-h-[130px]">
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Bulan {{ $tagihanAktif ? $tagihanAktif->bulan_tagihan : now()->translatedFormat('F') }}
                    </p>
                    <p class="text-2xs text-gray-400 font-medium">TOTAL TAGIHAN</p>
                    <h2 class="text-2xl font-black text-blue-600 mt-2">
                        Rp {{ $tagihanAktif ? number_format($tagihanAktif->total_tagihan, 0, ',', '.') : '0' }}
                    </h2>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-between min-h-[130px]">
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 {{ $tagihanAktif ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-green-50 text-green-600 border border-green-100' }} text-[10px] font-bold rounded-lg uppercase">
                            {{ $tagihanAktif ? 'Belum Bayar' : 'Lunas' }}
                        </span>
                    </div>
                    <p class="text-xs font-bold text-gray-400 mt-2">Status Pembayaran</p>
                    <h3 class="text-base font-black text-gray-800 mt-1">
                        {{ $tagihanAktif ? 'Jatuh Tempo: 10 ' . substr($tagihanAktif->bulan_tagihan, 0, 3) : 'Tagihan bulan ini aman!' }}
                    </h3>
                </div>
                <div class="p-3 {{ $tagihanAktif ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }} rounded-xl">
                    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-between min-h-[130px]">
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        {{ Auth::user()->kamar->kos->nama_kos ?? 'KosKitaa' }}
                    </p>
                    <p class="text-2xs text-gray-400 font-medium">Unit Kamar</p>
                    <h2 class="text-2xl font-black text-gray-900 mt-2">
                        {{ Auth::user()->kamar ? 'Kamar ' . Auth::user()->kamar->no_kamar : 'Belum di-plot' }}
                    </h2>
                </div>
                <div class="p-3 bg-gray-50 text-gray-500 rounded-xl">
                    <i data-lucide="door-closed" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-gray-50/20">
                <h3 class="text-base font-black text-gray-900">Riwayat Terakhir</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 bg-gray-50/50">
                            <th class="p-4 pl-6">Tanggal</th>
                            <th class="p-4">Deskripsi</th>
                            <th class="p-4">Jumlah</th>
                            <th class="p-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm font-medium text-gray-700">
                        @forelse($riwayatTerakhir as $r)
                            <tr class="hover:bg-gray-50/40 transition">
                                <td class="p-4 pl-6 text-xs text-gray-500">
                                    {{ $r->tanggal_tagihan ? \Carbon\Carbon::parse($r->tanggal_tagihan)->format('d M Y') : now()->format('d M Y') }}
                                </td>
                                <td class="p-4 font-bold text-gray-900 text-xs">
                                    Sewa Kamar {{ Auth::user()->kamar->no_kamar ?? '' }} - {{ $r->bulan_tagihan }}
                                </td>
                                <td class="p-4 text-xs font-black text-gray-800">
                                    Rp {{ number_format($r->total_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2.5 py-0.5 bg-green-50 text-green-600 text-2xs font-bold rounded-full border border-green-100">
                                        Lunas
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-gray-400 text-xs font-medium">Belum ada riwayat pembayaran yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-50 bg-gray-50/10 text-center">
                <a href="{{ route('penghuni.tagihan') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Lihat Semua Riwayat</a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-lg shadow-blue-100 relative overflow-hidden flex flex-col justify-between min-h-[200px]">
                <div class="space-y-2 z-10">
                    <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center">
                        <i data-lucide="message-square" class="w-4 h-4 text-white"></i>
                    </div>
                    <h3 class="text-lg font-black tracking-tight mt-4">Butuh Bantuan?</h3>
                    <p class="text-xs text-blue-100/90 leading-relaxed font-medium">Hubungi Admin langsung via WhatsApp untuk kendala fasilitas atau pembayaran.</p>
                </div>
                <a href="https://wa.me/62895610773523?text=Halo%20Admin%20KosKitaa%2C%20saya%20mau%20komplain..." target="_blank" class="w-full bg-white text-blue-600 hover:bg-blue-50 transition text-center font-bold py-3 rounded-xl text-xs flex items-center justify-center gap-2 z-10 shadow-sm mt-4">
                    Hubungi Admin <i data-lucide="send" class="w-3.5 h-3.5"></i>
                </a>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-blue-500 rounded-full opacity-20"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 space-y-4">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Informasi Hunian</h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="wifi" class="w-4 h-4"></i></div>
                        <div>
                            <p class="text-3xs font-bold text-gray-400 uppercase">Wifi Password</p>
                            <p class="text-xs font-bold text-gray-800">KosKitaa_Free123</p>
                        </div>
                    </div>
                    <a href="https://wa.me/628123456789?text=Halo%20Ibu%20Kos%2C%20saya%20penghuni%20kamar%20{{ Auth::user()->kamar->no_kamar ?? '' }}..." target="_blank" class="flex items-center gap-3 hover:bg-gray-50 p-1 rounded-xl transition">
                        <div class="p-2 bg-green-50 text-green-600 rounded-lg"><i data-lucide="phone" class="w-4 h-4"></i></div>
                        <div>
                            <p class="text-3xs font-bold text-gray-400 uppercase">WhatsApp Admin Kos</p>
                            <p class="text-xs font-bold text-gray-800 hover:text-blue-600">0851-5733-2408</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection