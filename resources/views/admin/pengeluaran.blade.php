@extends('layouts.admin')

@section('title', 'Data Pengeluaran - KOSKITAA')

@section('content')
<div class="p-8 max-w-6xl mx-auto">

    @if(session('success'))
        <div class="p-4 mb-6 bg-green-50 border border-green-200 text-green-700 font-bold text-sm rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Pengeluaran</h1>
            <p class="text-sm text-gray-500 mt-0.5">Lacak dan kelola semua biaya operasional properti Anda secara efisien.</p>
        </div>
        <button onclick="openModal()"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition shadow-sm whitespace-nowrap">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengeluaran Baru
        </button>
    </div>

    {{-- Summary Cards --}}
    @php
        $totalBulanIni = $pengeluarans->where('created_at', '>=', now()->startOfMonth())->sum('nominal');
        $terbesar = $pengeluarans->sortByDesc('nominal')->first();
        $anggaran = 12000000; // bisa dari config atau DB
        $terpakai = $pengeluarans->sum('nominal');
        $sisa = $anggaran - $terpakai;
        $persenTerpakai = $anggaran > 0 ? min(100, round(($terpakai / $anggaran) * 100)) : 0;
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        {{-- Total Bulan Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Pengeluaran Bulan Ini</p>
            <p class="text-2xl font-black text-gray-900">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</p>
            <p class="text-xs text-green-500 font-bold mt-1">↗ dari bulan lalu</p>
        </div>
        {{-- Terbesar --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pengeluaran Terbesar</p>
            <p class="text-base font-black text-gray-900">{{ $terbesar->jenis_pengeluaran ?? '-' }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Total: Rp {{ number_format($terbesar->nominal ?? 0, 0, ',', '.') }}</p>
        </div>
        {{-- Anggaran --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Anggaran Tersisa</p>
            <p class="text-2xl font-black text-gray-900">Rp {{ number_format(max(0, $sisa), 0, ',', '.') }}</p>
            <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                <div class="bg-blue-500 h-1.5 rounded-full transition-all" style="width: {{ $persenTerpakai }}%"></div>
            </div>
            <p class="text-xs text-gray-400 font-medium mt-1">{{ $persenTerpakai }}% digunakan</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">

        {{-- Filter bar --}}
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <select class="text-xs font-semibold text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                <option>Semua Kategori</option>
                <option>Listrik</option>
                <option>Air</option>
                <option>Pemeliharaan</option>
                <option>Darurat</option>
                <option>Wifi</option>
            </select>
            <input type="date" class="text-xs font-semibold text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            <div class="sm:ml-auto">
                <a href="#"
                   class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Ekspor Excel
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4 pl-6">Tanggal</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Deskripsi</th>
                        <th class="p-4">Jumlah</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @php
                        $kategoriColors = [
                            'Listrik'      => 'bg-yellow-50 text-yellow-600',
                            'Air'          => 'bg-blue-50 text-blue-600',
                            'Pemeliharaan' => 'bg-orange-50 text-orange-500',
                            'Darurat'      => 'bg-red-50 text-red-500',
                            'Wifi'         => 'bg-purple-50 text-purple-600',
                        ];
                        $dotColors = [
                            'Listrik'      => 'bg-yellow-400',
                            'Air'          => 'bg-blue-400',
                            'Pemeliharaan' => 'bg-orange-400',
                            'Darurat'      => 'bg-red-400',
                            'Wifi'         => 'bg-purple-400',
                        ];
                    @endphp
                    @forelse($pengeluarans as $p)
                        @php
                            $kategori = $p->jenis_pengeluaran ?? 'Lainnya';
                            $colorClass = $kategoriColors[$kategori] ?? 'bg-gray-100 text-gray-500';
                            $dotClass = $dotColors[$kategori] ?? 'bg-gray-400';
                            $statusLunas = ($p->status ?? 'lunas') === 'lunas';
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">

                            {{-- Tanggal --}}
                            <td class="p-4 pl-6 text-gray-400 text-xs whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
                            </td>

                            {{-- Kategori --}}
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $colorClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ $kategori }}
                                </span>
                            </td>

                            {{-- Deskripsi --}}
                            <td class="p-4 text-gray-600 text-xs">
                                {{ $p->deskripsi ?? '-' }}
                            </td>

                            {{-- Jumlah --}}
                            <td class="p-4 font-bold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($p->nominal, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="p-4">
                                @if($statusLunas)
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-md border border-green-100">LUNAS</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-md border border-amber-100">TERTUNDA</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="p-4 text-center">
                                <div class="relative inline-block">
                                    <button onclick="toggleDropdown('drop-{{ $p->id_pengeluaran }}')"
                                            class="p-1.5 hover:bg-gray-100 rounded-lg transition text-gray-400 hover:text-gray-700">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                        </svg>
                                    </button>
                                    <div id="drop-{{ $p->id_pengeluaran }}"
                                         class="hidden absolute right-0 mt-1 w-36 bg-white rounded-xl border border-gray-100 shadow-lg z-20 overflow-hidden">
                                        <button onclick="editData({{ json_encode($p) }}); toggleDropdown('drop-{{ $p->id_pengeluaran }}')"
                                                class="flex items-center gap-2 w-full px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.pengeluaran.destroy', $p->id_pengeluaran) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus data ini?')">
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
                            <td colspan="6" class="p-10 text-center text-gray-400 text-xs">Belum ada data pengeluaran tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($pengeluarans, 'hasPages') && $pengeluarans->hasPages())
        <div class="p-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $pengeluarans->firstItem() }}-{{ $pengeluarans->lastItem() }} dari {{ $pengeluarans->total() }} entri
            </p>
            <div class="flex items-center gap-1">
                @if($pengeluarans->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 border border-gray-100 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $pengeluarans->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 transition text-xs">‹</a>
                @endif
                @foreach($pengeluarans->getUrlRange(1, $pengeluarans->lastPage()) as $page => $url)
                    @if($page == $pengeluarans->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 border border-gray-200 hover:bg-gray-50 transition text-xs font-medium">{{ $page }}</a>
                    @endif
                @endforeach
                @if($pengeluarans->hasMorePages())
                    <a href="{{ $pengeluarans->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 transition text-xs">›</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 border border-gray-100 cursor-not-allowed text-xs">›</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Alokasi Biaya Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-5">Alokasi Biaya</h3>
        <div class="flex flex-col sm:flex-row items-center gap-8">
            {{-- Donut --}}
            <div class="relative flex-shrink-0">
                <canvas id="alokasiChart" width="140" height="140"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-xs text-gray-400 font-semibold">Total</span>
                    <span class="text-sm font-black text-gray-800">100%</span>
                </div>
            </div>
            {{-- Legend --}}
            <div class="flex flex-col gap-2 w-full">
                @php
                    $totalAll = $pengeluarans->sum('nominal') ?: 1;
                    $grouped = $pengeluarans->groupBy('jenis_pengeluaran')->map(fn($g) => $g->sum('nominal'))->sortByDesc(fn($v) => $v)->take(5);
                    $legendColors = ['#3b82f6','#ef4444','#f59e0b','#8b5cf6','#10b981'];
                    $ci = 0;
                @endphp
                @foreach($grouped as $label => $amount)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $legendColors[$ci] }}"></span>
                            <span class="text-xs font-semibold text-gray-600">{{ $label }}</span>
                        </div>
                        <span class="text-xs font-bold text-gray-500">{{ round(($amount / $totalAll) * 100) }}%</span>
                    </div>
                    @php $ci++ @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div id="modalPengeluaran" class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl p-7 w-full max-w-sm shadow-xl border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 id="modalTitle" class="text-base font-black text-gray-900">Tambah Pengeluaran</h2>
            <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 rounded-lg transition text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="formPengeluaran" action="{{ route('admin.pengeluaran.store') }}" method="POST">
            @csrf
            <div id="methodField"></div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis / Kategori</label>
                    <select name="jenis_pengeluaran" id="jenis_pengeluaran"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" required>
                        <option value="">Pilih kategori...</option>
                        <option value="Listrik">Listrik</option>
                        <option value="Air">Air</option>
                        <option value="Pemeliharaan">Pemeliharaan</option>
                        <option value="Darurat">Darurat</option>
                        <option value="Wifi">Wifi</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <input type="text" name="deskripsi" id="deskripsi"
                           placeholder="Contoh: Tagihan Listrik PLN - Oktober"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nominal (Rp)</label>
                    <input type="number" name="nominal" id="nominal"
                           placeholder="0"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" id="status"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                        <option value="lunas">Lunas</option>
                        <option value="tertunda">Tertunda</option>
                    </select>
                </div>
                <input type="hidden" name="id_admin" value="{{ auth('admin')->id() }}">
                <input type="hidden" name="id_kos" value="1">
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 py-2.5 rounded-xl text-xs font-bold text-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 py-2.5 rounded-xl text-xs font-bold text-white transition shadow-sm shadow-blue-100">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    // Modal
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Pengeluaran';
        document.getElementById('formPengeluaran').action = '{{ route('admin.pengeluaran.store') }}';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('jenis_pengeluaran').value = '';
        document.getElementById('nominal').value = '';
        document.getElementById('deskripsi') && (document.getElementById('deskripsi').value = '');
        document.getElementById('modalPengeluaran').classList.remove('hidden');
        document.getElementById('modalPengeluaran').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalPengeluaran').classList.add('hidden');
        document.getElementById('modalPengeluaran').classList.remove('flex');
    }

    function editData(p) {
        document.getElementById('modalTitle').textContent = 'Edit Pengeluaran';
        document.getElementById('formPengeluaran').action = `/admin/pengeluaran/${p.id_pengeluaran}`;
        document.getElementById('methodField').innerHTML = `<input type="hidden" name="_method" value="PUT">`;
        document.getElementById('jenis_pengeluaran').value = p.jenis_pengeluaran ?? '';
        document.getElementById('nominal').value = p.nominal ?? '';
        if (document.getElementById('deskripsi')) document.getElementById('deskripsi').value = p.deskripsi ?? '';
        if (document.getElementById('status')) document.getElementById('status').value = p.status ?? 'lunas';
        openModal();
    }

    // Dropdown
    function toggleDropdown(id) {
        document.querySelectorAll('[id^="drop-"]').forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[id^="drop-"]') && !e.target.closest('button[onclick^="toggleDropdown"]')) {
            document.querySelectorAll('[id^="drop-"]').forEach(d => d.classList.add('hidden'));
        }
    });

    // Donut Chart
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('alokasiChart');
        if (!ctx) return;
        const labels = @json($grouped->keys());
        const data   = @json($grouped->values());
        const colors = ['#3b82f6','#ef4444','#f59e0b','#8b5cf6','#10b981'];
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '72%',
                plugins: { legend: { display: false }, tooltip: { enabled: true } },
                animation: { animateRotate: true, duration: 800 }
            }
        });
    });
</script>
@endsection