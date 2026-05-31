<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penghuni Baru - KOSKITAA</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 antialiased flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-xl w-full max-w-md border border-gray-100">
        
        <h2 class="text-3xl font-black text-center text-blue-600 tracking-wide">KOSKITAA</h2>
        <p class="text-gray-500 text-center font-medium mt-1 mb-8 text-sm">Pendaftaran Akun Anak Kos Baru</p>
        
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-semibold">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penghuni.register.submit') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="nama_penghuni" value="{{ old('nama_penghuni') }}" required placeholder="Masukkan nama lengkap Anda" 
                    class="w-full px-4 py-3 bg-gray-50 text-gray-800 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email (harus @gmail.com)" 
                    class="w-full px-4 py-3 bg-gray-50 text-gray-800 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nomor HP / WhatsApp</label>
                <input type="tel" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Contoh: 081234567890" 
                    class="w-full px-4 py-3 bg-gray-50 text-gray-800 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                    class="w-full px-4 py-3 bg-gray-50 text-gray-800 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm mb-2">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 shadow-md hover:shadow-lg active:scale-[0.98] transition-all text-sm tracking-wide cursor-pointer">
                    Daftar Sekarang
                </button>
            </div>
            
            <p class="text-xs text-center text-gray-500 font-medium mt-6">
                Sudah punya akun kos? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Login Di Sini</a>
            </p>
        </form>
    </div>

</body>
</html>