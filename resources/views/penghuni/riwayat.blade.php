@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Riwayat Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Lihat dan pantau rekam jejak pembayaran sewa kos Anda yang telah lunas.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center gap-2">
            <i data-lucide="history" class="w-4 h-4 text-blue-500"></i>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Arsip Transaksi Sukses</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/70 border-b border-gray-50">
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4">ID Transaksi</th>
                        <th class="px-6 py-4">Tanggal Bayar</th>
                        <th class="px-6 py-4">Periode Tagihan</th>
                        <th class="px-6 py-4">Nominal Lunas</th>
                        <th class="px-6 py-4 text-center">Bukti</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($riwayatPembayaran as $r)
                        <tr class="hover:bg-gray-50/40 transition">
                            <td class="px-6 py-4 text-xs font-bold text-gray-900">
                                #TRX-{{ str_pad($r->id_pembayaran, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-gray-500">
                                {{ \Carbon\Carbon::parse($r->tanggal_pembayaran)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-800">
                                {{ $r->tagihan->bulan_tagihan ?? 'N/A' }} {{ \Carbon\Carbon::parse($r->tagihan->tanggal_tagihan ?? now())->format('Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-black text-blue-600">
                                Rp {{ number_format($r->tagihan->total_tagihan ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ asset('storage/' . $r->bukti_pembayaran) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-500 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-100 transition">
                                    <i data-lucide="eye" class="w-3 h-3"></i> Lihat Foto
                                </a>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 border border-green-100 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                                    Success
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center space-y-2">
                                <div class="w-10 h-10 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="folder-open" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-xs font-bold text-gray-700">Belum Ada Transaksi</h4>
                                <p class="text-3xs text-gray-400 font-medium max-w-xs mx-auto">Semua riwayat pembayaran kos lo yang sudah sukses diverifikasi admin bakal diarsip rapi di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection