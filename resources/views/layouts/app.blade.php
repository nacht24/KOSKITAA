<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - KOSKITAA</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased flex min-h-screen">

    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between fixed h-full z-40">
        <div class="p-6 space-y-8">
            <div>
                <h1 class="text-xl font-black text-blue-600 tracking-tight">KosKitaa</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Residential System</p>
            </div>

            <nav class="space-y-1.5">
                <a href="{{ route('penghuni.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('penghuni.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </a>
                
                <a href="{{ route('penghuni.tagihan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('penghuni.tagihan') || Route::is('penghuni.upload') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                    <i data-lucide="credit-card" class="w-4 h-4"></i> Tagihan
                </a>

                @auth
                    <a href="{{ isset($tagihanAktif) && $tagihanAktif ? route('penghuni.upload', $tagihanAktif->id_tagihan) : '/penghuni/upload/latest' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('penghuni.upload') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                        <i data-lucide="upload-cloud" class="w-4 h-4"></i> Upload Pembayaran
                    </a>
                @else
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-300 cursor-not-allowed opacity-50 transition">
                        <i data-lucide="upload-cloud" class="w-4 h-4"></i> Upload Pembayaran
                    </a>
                @endauth

                <a href="{{ route('penghuni.riwayat') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('penghuni.riwayat') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                    <i data-lucide="history" class="w-4 h-4"></i> Riwayat Pembayaran
                </a>
            </nav>
        </div>

        <div class="p-6 border-t border-gray-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition border border-transparent hover:border-red-100/30">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 pl-64 flex flex-col min-h-screen">
        
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-end px-8 fixed top-0 right-0 left-64 z-30">
            <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 py-1.5 pl-3 pr-1.5 rounded-full shadow-2xs">
                <span class="text-xs font-bold text-gray-700">{{ auth()->user()->nama_penghuni ?? 'User Kos' }}</span>
                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center font-bold text-xs text-gray-600 uppercase border border-gray-300/40 shadow-inner">
                    {{ substr(auth()->user()->nama_penghuni ?? 'NN', 0, 2) }}
                </div>
            </div>
        </header>

        <main class="flex-1 p-8 pt-28 bg-gray-50">
            @yield('content') 
        </main>
        
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>