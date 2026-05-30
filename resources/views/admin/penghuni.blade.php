@extends('layouts.admin')

@section('title', 'Manajemen Penyewa - KOSKITAA')

@section('content')
<div class="p-8 space-y-8 max-w-6xl mx-auto">

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Penyewa</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Kelola data penyewa, durasi sewa, dan status kontrak.</p>
        </div>
        <button type="button" onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition shadow-lg shadow-blue-100 flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Penyewa Baru
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-xl shadow-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Penyewa</p>
                    <h2 class="text-3xl font-black text-gray-900 leading-none">{{ $semuaPenghuni->count() }}</h2>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-2 flex justify-start">
                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-lg border border-blue-100">
                    +{{ $semuaPenghuni->count() }} bln ini
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Penyewa Aktif</p>
                    <h2 class="text-3xl font-black text-gray-900 leading-none">{{ $semuaPenghuni->whereNotNull('id_kamar')->count() }}</h2>
                </div>
                <div class="p-3 bg-green-50 text-green-600 rounded-xl flex-shrink-0">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
            </div>
            <div></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jatuh Tempo</p>
                    <h2 class="text-3xl font-black text-red-600 leading-none">0</h2>
                </div>
                <div class="p-3 bg-red-50 text-red-500 rounded-xl flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-2 flex justify-start">
                <span class="bg-red-50 text-red-600 text-[10px] font-black px-2 py-0.5 rounded-lg border border-red-100 uppercase tracking-wide">
                    Penting
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between min-h-[140px]">
            <div class="flex items-start justify-between">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                    <h2 class="text-3xl font-black text-gray-900 leading-none">{{ $semuaPenghuni->whereNull('id_kamar')->count() }}</h2>
                </div>
                <div class="p-3 bg-gray-50 text-gray-400 rounded-xl flex-shrink-0">
                    <i data-lucide="hourglass" class="w-5 h-5"></i>
                </div>
            </div>
            <div></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/20">
            <h3 class="text-base font-black text-gray-900">Daftar Penyewa</h3>
            <div class="flex items-center gap-2">
                <button type="button" class="px-3 py-1.5 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 transition flex items-center gap-1"><i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter</button>
                <button type="button" class="px-3 py-1.5 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 transition flex items-center gap-1"><i data-lucide="download" class="w-3.5 h-3.5"></i> Export</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 bg-gray-50/50">
                        <th class="p-4 pl-6">Nama Penyewa</th>
                        <th class="p-4">Nomor Kamar</th>
                        <th class="p-4">Tanggal Masuk</th>
                        <th class="p-4">Durasi Sewa</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-medium text-gray-700">
                    @forelse($semuaPenghuni as $p)
                    <tr class="hover:bg-gray-50/40 transition">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 text-gray-600 font-bold text-xs rounded-full flex items-center justify-center uppercase border border-gray-200">
                                    {{ substr($p->nama_penghuni ?? 'NN', 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm leading-tight">{{ $p->nama_penghuni }}</h4>
                                    <p class="text-2xs font-medium text-gray-400 mt-0.5">{{ $p->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4">
                            @if($p->id_kamar)
                                <span class="font-bold text-blue-600 bg-blue-50/50 px-2.5 py-1 rounded-lg text-xs border border-blue-100/50">
                                    {{ $p->kamar->no_kamar ?? 'N/A' }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs font-medium">-</span>
                            @endif
                        </td>

                        <td class="p-4 text-gray-500 text-xs">
                            {{ $p->created_at ? $p->created_at->format('d M Y') : '12 Jan 2024' }}
                        </td>

                        <td class="p-4 text-gray-600 text-sm font-semibold">
                            {{ $p->durasi_sewa ?? '12' }} Bulan
                        </td>

                        <td class="p-4">
                            @if($p->id_kamar)
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 text-2xs font-bold rounded-full">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-2xs font-bold rounded-full">Menunggu</span>
                            @endif
                        </td>

                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                @if(!$p->id_kamar)
                                    <select onchange="submitPlotKamar(this, '{{ $p->id_penghuni }}')" class="px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                                        <option value="">-- Plot Kamar --</option>
                                        @foreach($kamarTersedia as $k)
                                            <option value="{{ $k->id_kamar }}">{{ $k->kos->nama_kos ?? 'Kos' }} ({{ $k->no_kamar }})</option>
                                        @endforeach
                                    </select>
                                @endif

                                <button type="button" onclick="openEditModal({{ json_encode($p) }})" class="p-1.5 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                
                                <button type="button" onclick="openDeleteModal('{{ $p->id_penghuni }}', '{{ $p->nama_penghuni }}')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400 text-xs">Belum ada akun anak kos mendaftar di sistem.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-base font-bold text-gray-900">Tambah Akun Penyewa Baru</h3>
            <button type="button" onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form action="{{ route('admin.penghuni') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                <input type="text" name="nama_penghuni" required placeholder="Contoh: Budi Santoso" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Email</label>
                <input type="email" name="email" required placeholder="budi@gmail.com" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Plot Unit Kamar (Tersedia)</label>
                <select name="id_kamar" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    @if($kamarTersedia->count() > 0)
                        <option value="">-- Pilih Kamar (Bisa Dikosongkan) --</option>
                        @foreach($kamarTersedia as $k)
                            <option value="{{ $k->id_kamar }}">{{ $k->kos->nama_kos ?? 'Kos' }} - Kamar {{ $k->no_kamar }}</option>
                        @endforeach
                    @else
                        <option value="">⚠️ Kamar Kos Penuh (0 Tersedia)</option>
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Durasi Sewa</label>
                <select name="durasi_sewa" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    <option value="1">1 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12" selected>12 Bulan</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('addModal')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 rounded-xl text-sm transition">Batal</button>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition shadow-sm">Simpan Penyewa</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-base font-bold text-gray-900">Edit Data Penyewa</h3>
            <button type="button" onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                <input type="text" id="edit_nama" name="nama_penghuni" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Email</label>
                <input type="email" id="edit_email" name="email" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pindah Unit Kamar</label>
                <select id="edit_kamar" name="id_kamar" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    <option value="">-- Kosongkan / Lepas Kamar --</option>
                    @foreach($kamarTersedia as $k)
                        <option value="{{ $k->id_kamar }}">{{ $k->kos->nama_kos ?? 'Kos' }} - Kamar {{ $k->no_kamar }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Durasi Sewa</label>
                <select id="edit_durasi" name="durasi_sewa" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    <option value="1">1 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12">12 Bulan</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('editModal')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 rounded-xl text-sm transition">Batal</button>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl text-center transform scale-95 transition-transform duration-300">
        <div class="w-14 h-14 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="trash-2" class="w-6 h-6"></i>
        </div>
        <h3 class="text-base font-black text-gray-900">Keluarkan Penghuni?</h3>
        <p class="text-xs text-gray-500 mt-2">Anda yakin ingin menghapus/mengeluarkan <span id="delete_target_name" class="font-bold text-gray-900"></span> dari sistem KosKitaa?</p>
        
        <form id="deleteForm" action="" method="POST" class="flex gap-3 mt-6">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('deleteModal')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 rounded-xl text-sm transition">Batal</button>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl text-sm transition shadow-sm">Ya, Keluarkan</button>
        </form>
    </div>
</div>

<form id="globalPlotForm" action="" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" id="globalPlotKamarId" name="id_kamar">
</form>

<script>
    function openModal(id) {
        const targetModal = document.getElementById(id);
        targetModal.classList.remove('hidden');
        setTimeout(() => {
            targetModal.classList.remove('opacity-0');
            targetModal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeModal(id) {
        const targetModal = document.getElementById(id);
        targetModal.querySelector('div').classList.add('scale-95');
        targetModal.classList.add('opacity-0');
        setTimeout(() => {
            targetModal.classList.add('hidden');
        }, 300);
    }

    function openAddModal() {
        openModal('addModal');
    }

    function openEditModal(penghuni) {
        document.getElementById('edit_nama').value = penghuni.nama_penghuni;
        document.getElementById('edit_email').value = penghuni.email;
        document.getElementById('edit_durasi').value = penghuni.durasi_sewa ?? '12';
        document.getElementById('edit_kamar').value = penghuni.id_kamar ?? '';

        document.getElementById('editForm').action = `/admin/penghuni/${penghuni.id_penghuni}`;
        
        openModal('editModal');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('delete_target_name').innerText = nama;
        document.getElementById('deleteForm').action = `/admin/penghuni/${id}`;
        
        openModal('deleteModal');
    }

    function submitPlotKamar(selectElement, penghuniId) {
        const kamarId = selectElement.value;
        if (!kamarId) return;

        if (confirm('Apakah Anda yakin ingin menempatkan penghuni ke kamar ini?')) {
            const form = document.getElementById('globalPlotForm');
            document.getElementById('globalPlotKamarId').value = kamarId;
            form.action = `/admin/penghuni/assign/${penghuniId}`;
            form.submit();
        } else {
            selectElement.value = "";
        }
    }
</script>
@endsection