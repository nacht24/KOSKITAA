@extends('layouts.admin')

@section('title', 'Manajemen Kamar - KOSKITAA')

@section('content')
<div class="p-8 space-y-8 max-w-6xl mx-auto">

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Kamar</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Kelola ketersediaan properti, penempatan penyewa, dan status pemeliharaan.</p>
        </div>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition shadow-lg shadow-blue-100 flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kamar Baru
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-xl shadow-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i data-lucide="bed" class="w-6 h-6"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kamar</p>
                <h2 class="text-3xl font-black text-gray-900 mt-1">{{ $semuaKamar->count() }}</h2>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl"><i data-lucide="check-square" class="w-6 h-6"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tersedia</p>
                <h2 class="text-3xl font-black text-gray-900 mt-1">
                    {{ $semuaKamar->whereIn('status_kamar', ['kosong', 'tersedia', 'Kosong', 'Tersedia'])->count() }}
                </h2>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i data-lucide="user-check" class="w-6 h-6"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Terisi</p>
                <h2 class="text-3xl font-black text-gray-900 mt-1">
                    {{ $semuaKamar->whereIn('status_kamar', ['terisi', 'Terisi'])->count() }}
                </h2>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-red-50 text-red-500 rounded-xl"><i data-lucide="wrench" class="w-6 h-6"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perbaikan</p>
                <h2 class="text-3xl font-black text-gray-900 mt-1">
                    {{ $semuaKamar->whereIn('status_kamar', ['perbaikan', 'Perbaikan'])->count() }}
                </h2>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 bg-gray-50/50">
                        <th class="p-4 pl-6">No. Kamar</th>
                        <th class="p-4">Foto</th>
                        <th class="p-4">Gedung</th>
                        <th class="p-4">Penyewa</th>
                        <th class="p-4">Harga Sewa</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-medium text-gray-700">
                    @forelse($semuaKamar as $kamar)
                    <tr class="hover:bg-gray-50/40 transition">
                        <td class="p-4 pl-6 font-black text-blue-600 text-base">{{ $kamar->no_kamar }}</td>
                        <td class="p-4">
                            <div class="w-16 h-10 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center text-xs">🖼️</div>
                        </td>
                        <td class="p-4 text-gray-500 text-xs">{{ $kamar->kos->nama_kos ?? 'Standar' }}</td>
                        <td class="p-4">
                            @if(strtolower($kamar->status_kamar) == 'terisi' && $kamar->penghuni->count() > 0)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-blue-50 text-blue-600 font-bold text-2xs rounded-full flex items-center justify-center uppercase">
                                        {{ substr($kamar->penghuni->first()->nama_penghuni ?? 'NN', 0, 2) }}
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm">{{ $kamar->penghuni->first()->nama_penghuni }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs font-medium">-</span>
                            @endif
                        </td>
                        <td class="p-4 font-bold">Rp {{ number_format($kamar->harga_kamar, 0, ',', '.') }}</td>
                        <td class="p-4">
                            @if(in_array(strtolower($kamar->status_kamar), ['kosong', 'tersedia']))
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 text-2xs font-bold rounded-full">● Tersedia</span>
                            @elseif(strtolower($kamar->status_kamar) == 'terisi')
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-2xs font-bold rounded-full">● Terisi</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-2xs font-bold rounded-full">● Perbaikan</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ json_encode($kamar) }})" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('admin.kamar.destroy', $kamar->id_kamar) }}" method="POST" onsubmit="return confirm('Hapus kamar ini?')">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.kamar.destroy', $kamar->id_kamar) }}')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-gray-400">Belum ada unit kamar kos terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="kamarModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 id="modalTitle" class="text-base font-bold text-gray-900">Tambah Unit Kamar</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="modalForm" action="{{ route('admin.kamar.store') }}" method="POST" class="space-y-4">
            @csrf
            <div id="methodContainer"></div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Gedung Lokasi</label>
                <select name="id_kos" id="input_id_kos" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($daftarKos as $kos)
                        <option value="{{ $kos->id_kos }}">{{ $kos->nama_kos }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Unit</label>
                <input type="text" name="no_kamar" id="input_no_kamar" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Sewa (Rp)</label>
                <input type="number" name="harga_kamar" id="input_harga_kamar" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-bold text-gray-800">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status</label>
                <select name="status_kamar" id="input_status_kamar" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    <option value="kosong">Kosong</option>
                    <option value="terisi">Terisi</option>
                    <option value="perbaikan">Perbaikan</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 rounded-xl text-sm transition">Batal</button>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl transform scale-95 transition-transform duration-300 text-center">
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-2">Hapus Unit Kamar?</h3>
        <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan. Data kamar akan dihapus permanen.</p>
        <form id="deleteForm" method="POST" class="flex gap-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 rounded-xl text-sm transition">Batal</button>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl text-sm transition shadow-sm">Ya, Hapus</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('kamarModal');
    const form = document.getElementById('modalForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');
    const storeUrl = "{{ route('admin.kamar.store') }}";

    function openAddModal() {
        modalTitle.innerText = "Tambah Unit Kamar Baru";
        form.action = storeUrl;
        methodContainer.innerHTML = "";
        document.getElementById('input_id_kos').value = "";
        document.getElementById('input_no_kamar').value = "";
        document.getElementById('input_harga_kamar').value = "";
        document.getElementById('input_status_kamar').value = "kosong";
        showModal();
    }

    function openEditModal(kamar) {
        modalTitle.innerText = "Edit Kamar: " + kamar.no_kamar;
        let updateUrl = "{{ route('admin.kamar.update', ':id') }}";
        form.action = updateUrl.replace(':id', kamar.id_kamar);
        methodContainer.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
        document.getElementById('input_id_kos').value = kamar.id_kos;
        document.getElementById('input_no_kamar').value = kamar.no_kamar;
        document.getElementById('input_harga_kamar').value = kamar.harga_kamar;
        
        let status = kamar.status_kamar ? kamar.status_kamar.toLowerCase().trim() : 'kosong';
        if (status === 'tersedia') status = 'kosong';
        document.getElementById('input_status_kamar').value = status;
        showModal();
    }

    function openDeleteModal(deleteUrl) {
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = deleteUrl;

        deleteModal.classList.remove('hidden');
        setTimeout(() => {
            deleteModal.classList.remove('opacity-0');
            deleteModal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteModal() {
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.classList.add('opacity-0');
        deleteModal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { deleteModal.classList.add('hidden'); }, 300);
    }

    function showModal() {
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); modal.querySelector('div').classList.remove('scale-95'); }, 10);
    }
    function closeModal() {
        modal.classList.add('opacity-0'); modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>
@endsection