@extends('layouts.admin')

@section('title', 'Pembayaran - KOSKITAA')

@section('content')
<div class="p-8 max-w-6xl mx-auto">

    @if(session('success'))
        <div class="p-4 mb-6 bg-green-50 border border-green-200 text-green-700 font-bold text-sm rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Verifikasi Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola dan verifikasi bukti pembayaran manual yang diunggah oleh penyewa.</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 transition flex items-center gap-2 shadow-sm">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Filter Berdasarkan Tanggal
            </button>
            <a href="#"
               class="text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-4 py-2.5 transition flex items-center gap-2 shadow-sm">
                Ekspor Laporan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menunggu Tinjauan</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-gray-900">{{ $menungguTinjauan ?? 0 }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Disetujui Bulan Ini</p>
            <span class="text-3xl font-black text-gray-900">{{ $disetujuiBulanIni ?? 0 }}</span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Dana Tertunda</p>
            <span class="text-2xl font-black text-gray-900">Rp {{ number_format($totalMemverifikasi ?? 0, 0, ',', '.') }}</span>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800">Antrean Verifikasi</h2>
            <div class="flex items-center gap-1 text-gray-400">
                <button class="p-1.5 hover:bg-gray-50 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                </button>
                <button class="p-1.5 hover:bg-gray-50 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4 pl-6">Tanggal</th>
                        <th class="p-4">Nama Penyewa</th>
                        <th class="p-4">Kamar#</th>
                        <th class="p-4">Jumlah</th>
                        <th class="p-4">Bukti</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                    @forelse($semuaPembayaran as $bayar)
                        @php
                            $status = $bayar->tagihan->status_pembayaran ?? 'belum_lunas';
                            $isLunas = $status === 'lunas';
                            $nama = $bayar->penghuni->nama_penghuni ?? 'Anak Kos';
                            $initials = strtoupper(substr($nama, 0, 2));
                            $colors = ['bg-blue-100 text-blue-600', 'bg-purple-100 text-purple-600', 'bg-green-100 text-green-600', 'bg-rose-100 text-rose-600', 'bg-amber-100 text-amber-600'];
                            $colorClass = $colors[crc32($nama) % count($colors)];
                            $tanggal = $bayar->created_at ?? now();
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">

                            <td class="p-4 pl-6 text-gray-500 text-xs whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                            </td>

                            <td class="p-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 {{ $colorClass }} font-bold text-xs rounded-full flex items-center justify-center flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm">{{ $nama }}</span>
                                </div>
                            </td>

                            <td class="p-4 text-gray-600 text-xs font-semibold">
                                {{ $bayar->penghuni->kamar->no_kamar ?? '-' }}
                            </td>

                            <td class="p-4 font-bold text-gray-900">
                                Rp {{ number_format($bayar->tagihan->total_tagihan ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="p-4">
                                @if($bayar->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $bayar->bukti_pembayaran) }}" target="_blank"
                                       class="flex items-center gap-1.5 group">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden flex items-center justify-center flex-shrink-0">
                                            <img src="{{ asset('storage/' . $bayar->bukti_pembayaran) }}"
                                                 alt="Bukti"
                                                 class="w-full h-full object-cover group-hover:opacity-80 transition"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                            <svg class="hidden w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 font-medium">Tidak ada file</span>
                                @endif
                            </td>

                            <td class="p-4">
                                @if($isLunas)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                                        Tertunda
                                    </span>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                @if(!$isLunas)
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.pembayaran.tolak', $bayar->id_pembayaran) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menolak pembayaran ini?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="w-7 h-7 flex items-center justify-center rounded-full bg-red-50 hover:bg-red-100 text-red-500 transition border border-red-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pembayaran.setujui', $bayar->id_pembayaran) }}" method="POST"
                                              onsubmit="return confirm('Konfirmasi bahwa uang transferan ini valid?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="w-7 h-7 flex items-center justify-center rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition border border-blue-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 font-semibold">
                                        Diverifikasi oleh<br>
                                        <span class="text-gray-500">{{ $bayar->verified_by ?? 'Admin' }}</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400 text-xs">Belum ada unggahan bukti pembayaran dari anak kos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($semuaPembayaran->hasPages())
        <div class="p-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $semuaPembayaran->firstItem() }} hingga {{ $semuaPembayaran->lastItem() }} dari {{ $semuaPembayaran->total() }} entri
            </p>
            <div class="flex items-center gap-1">
                @if($semuaPembayaran->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 border border-gray-100 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $semuaPembayaran->previousPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 transition text-xs">‹</a>
                @endif

                @foreach($semuaPembayaran->getUrlRange(1, $semuaPembayaran->lastPage()) as $page => $url)
                    @if($page == $semuaPembayaran->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-bold border border-blue-600">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 border border-gray-200 hover:bg-gray-50 transition text-xs font-medium">{{ $page }}</a>
                    @endif
                @endforeach

                @if($semuaPembayaran->hasMorePages())
                    <a href="{{ $semuaPembayaran->nextPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 transition text-xs">›</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 border border-gray-100 cursor-not-allowed text-xs">›</span>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>
@endsection