@extends('layouts.admin')

@section('title', 'Laporan Keuangan - KOSKITAA')

@section('content')
<div class="p-8 max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Laporan Keuangan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan performa untuk periode fiskal saat ini.</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Filter Periode --}}
            <div class="relative">
                <select onchange="window.location.href='?bulan='+this.value"
                        class="appearance-none text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg pl-8 pr-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition shadow-sm cursor-pointer">
                    <option value="3"  {{ $bulan == 3  ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="6"  {{ $bulan == 6  ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>12 Bulan Terakhir</option>
                </select>
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            {{-- Ekspor --}}
            <a href="#"
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition shadow-sm whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Ekspor Laporan
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- Total Pendapatan Bersih --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Pendapatan Bersih</p>
            <p class="text-2xl font-black text-gray-900">Rp {{ number_format($totalBersih, 0, ',', '.') }}</p>
            {{-- Decorative background number --}}
            <div class="absolute -right-4 -bottom-4 text-8xl font-black text-gray-50 select-none pointer-events-none leading-none">
                Rp
            </div>
        </div>

        {{-- Total Pemasukan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pemasukan</p>
                <span class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </span>
            </div>
            <p class="text-xl font-black text-gray-900">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pengeluaran</p>
                <span class="w-7 h-7 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </span>
            </div>
            <p class="text-xl font-black text-gray-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Line Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-bold text-gray-800">Perbandingan Pemasukan vs Pengeluaran</h2>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-xs font-semibold text-gray-500">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>Pemasukan
                </span>
                <span class="flex items-center gap-1.5 text-xs font-semibold text-gray-500">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300 inline-block"></span>Pengeluaran
                </span>
            </div>
        </div>
        <canvas id="laporanChart" height="100"></canvas>
    </div>

    {{-- Detail Pengeluaran --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800">Detail Pengeluaran</h2>
            <a href="{{ route('admin.pengeluaran.index') }}" class="text-xs font-bold text-blue-500 hover:text-blue-700 transition">
                Lihat Semua Pengeluaran
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4 pl-6">Kategori</th>
                        <th class="p-4">ID Referensi</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Jumlah</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $iconMap = [
                            'Listrik'      => ['icon' => '⚡', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-500'],
                            'Air'          => ['icon' => '💧', 'bg' => 'bg-blue-50',   'text' => 'text-blue-500'],
                            'Pemeliharaan' => ['icon' => '🔧', 'bg' => 'bg-orange-50', 'text' => 'text-orange-500'],
                            'Internet'     => ['icon' => '📶', 'bg' => 'bg-purple-50', 'text' => 'text-purple-500'],
                            'Darurat'      => ['icon' => '🚨', 'bg' => 'bg-red-50',    'text' => 'text-red-500'],
                            'Wifi'         => ['icon' => '📶', 'bg' => 'bg-purple-50', 'text' => 'text-purple-500'],
                        ];
                        $defaultIcon = ['icon' => '📋', 'bg' => 'bg-gray-100', 'text' => 'text-gray-500'];
                    @endphp

                    @forelse($detailPengeluaran as $p)
                        @php
                            $kategori = $p->jenis_pengeluaran ?? 'Lainnya';
                            $ico = $iconMap[$kategori] ?? $defaultIcon;
                            $statusLunas = ($p->status ?? 'lunas') === 'lunas';

                            // ID Referensi — generate dari kategori + tahun + id jika tidak ada kolom tersendiri
                            $prefixMap = [
                                'Listrik'      => 'ELC',
                                'Air'          => 'WTR',
                                'Pemeliharaan' => 'MNT',
                                'Internet'     => 'INT',
                                'Wifi'         => 'INT',
                                'Darurat'      => 'EMG',
                            ];
                            $prefix = $prefixMap[$kategori] ?? 'OTH';
                            $idRef  = $p->id_referensi
                                      ?? ($prefix . '-' . \Carbon\Carbon::parse($p->created_at)->format('Y') . '-' . str_pad($p->id_pengeluaran, 4, '0', STR_PAD_LEFT));
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">

                            {{-- Kategori --}}
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $ico['bg'] }} flex items-center justify-center text-sm flex-shrink-0">
                                        {{ $ico['icon'] }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">{{ $kategori }}</p>
                                        <p class="text-xs text-gray-400">{{ $p->deskripsi ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- ID Referensi --}}
                            <td class="p-4 text-xs font-mono font-semibold text-gray-500">
                                {{ $idRef }}
                            </td>

                            {{-- Tanggal --}}
                            <td class="p-4 text-xs text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
                            </td>

                            {{-- Jumlah --}}
                            <td class="p-4 text-sm font-bold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($p->nominal, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="p-4">
                                @if($statusLunas)
                                    <span class="text-xs font-bold text-green-600">Lunas</span>
                                @else
                                    <span class="text-xs font-bold text-amber-500">Tertunda</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="p-4 text-center">
                                <div class="relative inline-block">
                                    <button onclick="toggleDrop('ld-{{ $p->id_pengeluaran }}')"
                                            class="p-1.5 hover:bg-gray-100 rounded-lg transition text-gray-400 hover:text-gray-700">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                        </svg>
                                    </button>
                                    <div id="ld-{{ $p->id_pengeluaran }}"
                                         class="hidden absolute right-0 mt-1 w-32 bg-white rounded-xl border border-gray-100 shadow-lg z-20 overflow-hidden">
                                        <a href="{{ route('admin.pengeluaran.index') }}"
                                           class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        <form action="{{ route('admin.pengeluaran.destroy', $p->id_pengeluaran) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="flex items-center gap-2 w-full px-4 py-2.5 text-xs font-semibold text-red-500 hover:bg-red-50 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400 text-xs">Belum ada data pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($detailPengeluaran, 'hasPages') && $detailPengeluaran->hasPages())
        <div class="p-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $detailPengeluaran->firstItem() }}-{{ $detailPengeluaran->lastItem() }} dari {{ $detailPengeluaran->total() }} entri
            </p>
            <div class="flex items-center gap-1">
                @if($detailPengeluaran->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 border border-gray-100 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $detailPengeluaran->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 transition text-xs">‹</a>
                @endif
                @foreach($detailPengeluaran->getUrlRange(1, $detailPengeluaran->lastPage()) as $page => $url)
                    @if($page == $detailPengeluaran->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 border border-gray-200 hover:bg-gray-50 transition text-xs font-medium">{{ $page }}</a>
                    @endif
                @endforeach
                @if($detailPengeluaran->hasMorePages())
                    <a href="{{ $detailPengeluaran->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 transition text-xs">›</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 border border-gray-100 cursor-not-allowed text-xs">›</span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    // Line Chart
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('laporanChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: @json($chartPemasukan),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.08)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#3b82f6',
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($chartPengeluaran),
                        borderColor: '#d1d5db',
                        backgroundColor: 'rgba(209,213,219,0.06)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#d1d5db',
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11 }, color: '#9ca3af' }
                    },
                    y: {
                        grid: { color: '#f3f4f6' },
                        border: { display: false, dash: [4,4] },
                        ticks: {
                            font: { size: 11 },
                            color: '#9ca3af',
                            callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'jt'
                        }
                    }
                }
            }
        });
    });

    // Dropdown
    function toggleDrop(id) {
        document.querySelectorAll('[id^="ld-"]').forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[id^="ld-"]') && !e.target.closest('button[onclick^="toggleDrop"]')) {
            document.querySelectorAll('[id^="ld-"]').forEach(d => d.classList.add('hidden'));
        }
    });
</script>
@endsection