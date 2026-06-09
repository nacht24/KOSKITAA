@extends('layouts.app')

@section('title', 'Upload Pembayaran')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Upload Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Selesaikan pembayaran tagihan bulanan Anda dengan mudah.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-4">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider">Detail Tagihan</h3>
                
                <div class="space-y-3 text-sm font-medium">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-400">ID Tagihan</span>
                        <span class="font-bold text-gray-900">#KM-{{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('Y-m') }}-0{{ $tagihan->id_tagihan }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-400">Bulan</span>
                        <span class="font-bold text-gray-900">{{ $tagihan->bulan_tagihan }} {{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-400">Tipe</span>
                        <span class="font-bold text-gray-900">Sewa Kamar</span>
                    </div>
                </div>

                <div class="pt-2">
                    <p class="text-3xs font-bold text-gray-400 uppercase tracking-wide">Total Pembayaran</p>
                    <h2 class="text-2xl font-black text-blue-600 mt-0.5">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</h2>
                </div>
            </div>

            <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-lg shadow-blue-100 space-y-4">
                <h3 class="text-xs font-bold text-blue-200 uppercase tracking-wider">Rekening Tujuan</h3>
                
                <div class="p-4 bg-white/10 rounded-xl border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="text-3xs font-black text-blue-100 uppercase bg-white/20 px-1.5 py-0.5 rounded">Bank BCA</span>
                        <p class="text-lg font-black text-white mt-2 tracking-wider">8830 1928 33</p>
                        <p class="text-3xs text-blue-200 font-semibold mt-0.5 uppercase tracking-wide">a.n. PT KOSKITAA PROPERTI</p>
                    </div>
                    <i data-lucide="copy" class="w-4 h-4 text-blue-200 cursor-pointer hover:text-white transition"></i>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <form action="{{ route('penghuni.tagihan.submit') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs flex flex-col min-h-[400px] justify-between space-y-6">
                @csrf
                <input type="hidden" name="id_tagihan" value="{{ $tagihan->id_tagihan ?? 0 }}">

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-black text-gray-900">Unggah Bukti Transfer</h3>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-md">Langkah 1 dari 1</span>
                    </div>

                    <div id="dropzone" class="w-full h-64 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center text-center p-6 bg-gray-50/50 hover:border-blue-400 focus-within:border-blue-400 transition cursor-pointer group relative overflow-hidden">
                        <img id="preview" class="absolute inset-0 w-full h-full object-contain p-2 hidden z-10" src="#" alt="Preview Bukti" />
                        
                        <div id="placeholder" class="flex flex-col items-center space-y-2 z-0">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-full group-hover:scale-110 transition duration-300">
                                <i data-lucide="file-up" class="w-6 h-6"></i>
                            </div>
                            <h4 class="text-xs font-black text-gray-800">Klik atau Tarik File Ke Sini</h4>
                            <p class="text-3xs text-gray-400 font-medium max-w-xs leading-relaxed">Format file: JPG, PNG (Maks. 5MB)</p>
                        </div>
                    </div>
                    <input type="file" name="bukti_transfer" id="fileInput" accept="image/*" required class="hidden">

                    <div class="space-y-1">
                        <label class="block text-3xs font-bold text-gray-400 uppercase tracking-wider">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="2" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 font-medium transition" placeholder="Contoh: Pembayaran bulan ini via m-BCA."></textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-50 flex justify-start">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-3 rounded-xl transition shadow-md shadow-blue-100 flex items-center gap-2 cursor-pointer">
                        <span>Kirim Bukti</span>
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-4">
        <div class="flex items-center gap-2 text-xs font-black text-gray-900 uppercase tracking-wider">
            <i data-lucide="info" class="w-4 h-4 text-blue-500"></i> Panduan Pembayaran
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-start gap-3">
                <span class="text-xl font-black text-gray-200 leading-none">01</span>
                <div>
                    <h4 class="text-xs font-black text-gray-800">Lakukan Transfer</h4>
                    <p class="text-3xs text-gray-400 font-medium mt-1 leading-relaxed">Transfer sesuai nominal tagihan ke rekening BCA kami sebelum tanggal 10 tiap bulannya.</p>
                </div>
            </div>
            
            <div class="flex items-start gap-3">
                <span class="text-xl font-black text-gray-200 leading-none">02</span>
                <div>
                    <h4 class="text-xs font-black text-gray-800">Foto Bukti Bayar</h4>
                    <p class="text-3xs text-gray-400 font-medium mt-1 leading-relaxed">Gunakan kamera atau screenshot m-Banking Anda. Pastikan detail transaksi terbaca jelas.</p>
                </div>
            </div>
            
            <div class="flex items-start gap-3">
                <span class="text-xl font-black text-gray-200 leading-none">03</span>
                <div>
                    <h4 class="text-xs font-bold text-gray-800">Tunggu Verifikasi</h4>
                    <p class="text-3xs text-gray-400 font-medium mt-1 leading-relaxed">Tim kami akan memproses verifikasi dalam waktu maksimal 1&times;24 jam kerja.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');

    dropzone.addEventListener('click', () => fileInput.click());

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-blue-400', 'bg-blue-50/10');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-blue-400', 'bg-blue-50/10');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-blue-400', 'bg-blue-50/10');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handlePreview(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            handlePreview(this.files[0]);
        }
    });

    function handlePreview(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection