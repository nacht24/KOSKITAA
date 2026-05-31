@extends('layouts.app')

@section('title', 'Upload Pembayaran')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Upload Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Selesaikan pembayaran tagihan bulanan Anda dengan mudah.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-xl shadow-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl text-sm font-bold shadow-sm">
            <div class="flex items-center gap-2 text-red-800 mb-1">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Unggah Bukti Gagal:
            </div>
            <ul class="list-disc list-inside text-xs font-medium text-red-600 pl-1 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 space-y-6">
                @if($tagihanAktif)
                    <div class="flex justify-between items-center">
                        @if($tagihanAktif->status_pembayaran === 'menunggu_verifikasi')
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-bold rounded-lg uppercase tracking-wider flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Menunggu Verifikasi Admin
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-red-50 text-red-600 border border-red-100 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                                Belum Dibayar • Jatuh Tempo 10 {{ substr($tagihanAktif->bulan_tagihan, 0, 3) }}
                            </span>
                        @endif
                        
                        <button type="button" class="text-xs border border-gray-200 text-gray-600 font-bold px-3 py-1.5 rounded-xl hover:bg-gray-50 flex items-center gap-1.5 transition">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                        </button>
                    </div>

                    <div>
                        <h2 class="text-lg font-black text-gray-900">Tagihan #KM-{{ \Carbon\Carbon::parse($tagihanAktif->tanggal_tagihan)->format('Y-m') }}-0{{ $tagihanAktif->id_tagihan }}</h2>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Periode Tagihan: {{ $tagihanAktif->bulan_tagihan }} {{ \Carbon\Carbon::parse($tagihanAktif->tanggal_tagihan)->format('Y') }}</p>
                    </div>
                    <hr class="border-gray-50">

                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-gray-50/40 p-4 rounded-xl border border-gray-100/50">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="bed" class="w-4 h-4"></i></div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">Sewa Kamar Bulanan</h4>
                                    <p class="text-3xs text-gray-400 font-medium mt-0.5">Standard Deluxe Room - Unit {{ Auth::user()->kamar->no_kamar ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-black text-gray-800">Rp {{ number_format(optional(Auth::user()->kamar)->harga_kamar ?? $tagihanAktif->total_tagihan, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center bg-gray-50/40 p-4 rounded-xl border border-gray-100/50">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">Trash & Maintenance</h4>
                                    <p class="text-3xs text-gray-400 font-medium mt-0.5">Monthly service fee</p>
                                </div>
                            </div>
                            <span class="text-xs font-black text-gray-800">Rp 0</span>
                        </div>
                    </div>
                    <hr class="border-gray-50">

                    <div class="flex justify-between items-center pt-2">
                        <div>
                            <p class="text-3xs font-bold text-gray-400 uppercase tracking-wide">Total Tagihan</p>
                            <h2 class="text-2xl font-black text-blue-600 mt-0.5">Rp {{ number_format($tagihanAktif->total_tagihan, 0, ',', '.') }}</h2>
                        </div>
                        
                        @if($tagihanAktif->status_pembayaran === 'menunggu_verifikasi')
                            <div class="bg-gray-100 text-gray-500 text-xs font-bold px-5 py-3 rounded-xl flex items-center gap-2 border border-gray-200">
                                <i data-lucide="lock" class="w-4 h-4"></i> Bukti Sedang Ditinjau
                            </div>
                        @else
                            <a href="{{ route('penghuni.upload', $tagihanAktif->id_tagihan) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-3 rounded-xl transition shadow-md shadow-blue-100 flex items-center gap-1.5">
                                Lanjut ke Bukti Pembayaran <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                @else
                    <div class="p-8 text-center bg-green-50/40 rounded-xl border border-green-100/50 space-y-2 flex flex-col items-center">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-1"><i data-lucide="sparkles" class="w-6 h-6"></i></div>
                        <h3 class="text-sm font-black text-green-800">Semua Tagihan Lunas!</h3>
                        <p class="text-xs text-green-600 font-medium max-w-sm">Mantap! Administrasi hunian lo aman terkendali, tidak ada tagihan aktif yang perlu dibayar bulan ini, cuy.</p>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 space-y-4">
                <div class="flex items-center gap-2 text-sm font-black text-gray-900">
                    <i data-lucide="info" class="w-4 h-4 text-blue-500"></i> Virtual Account Info
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-3xs font-black text-gray-400 bg-white border border-gray-200/60 px-1.5 py-0.5 rounded uppercase">Bank BCA</span>
                            <p class="text-sm font-black text-gray-800 mt-1.5 tracking-wider">8832 0012 3456</p>
                        </div>
                        <i data-lucide="copy" class="w-3.5 h-3.5 text-gray-400 cursor-pointer hover:text-gray-900"></i>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-3xs font-black text-gray-400 bg-white border border-gray-200/60 px-1.5 py-0.5 rounded uppercase">Bank Mandiri</span>
                            <p class="text-sm font-black text-gray-800 mt-1.5 tracking-wider">123 00 0987 6543</p>
                        </div>
                        <i data-lucide="copy" class="w-3.5 h-3.5 text-gray-400 cursor-pointer hover:text-gray-900"></i>
                    </div>
                </div>
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
                <a href="https://wa.me/628123456789?text=Halo%20Admin%20KosKitaa%2C%20saya%20mau%20komplain..." target="_blank" class="w-full bg-white text-blue-600 hover:bg-blue-50 transition text-center font-bold py-3 rounded-xl text-xs flex items-center justify-center gap-2 z-10 shadow-sm mt-4">
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
                            <p class="text-3xs font-bold text-gray-400 uppercase">WhatsApp Ibu Kos</p>
                            <p class="text-xs font-bold text-gray-800 hover:text-blue-600">0812-3456-789</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection