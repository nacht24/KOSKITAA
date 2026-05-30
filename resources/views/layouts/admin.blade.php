<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KOSKITAA - Admin')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen">
        
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between p-6 shrink-0 sticky top-0 h-screen">
            <div class="space-y-8">
                <div>
                    <h2 class="text-xl font-black text-blue-600 tracking-tight">KosKitaa</h2>
                    <p class="text-xs text-gray-400 font-medium tracking-wide">Residential Management</p>
                </div>

                <nav class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.kamar') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('admin.kamar*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                        <i data-lucide="bed" class="w-4 h-4"></i> Kamar
                    </a>
                    <a href="{{ route('admin.penghuni') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('admin.penghuni*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                        <i data-lucide="users" class="w-4 h-4"></i> Penyewa
                    </a>
                    <a href="{{ route('admin.pembayaran') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('admin.pembayaran*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                        <i data-lucide="credit-card" class="w-4 h-4"></i> Pembayaran
                    </a>
                    <a href="{{ route('admin.pengeluaran.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('admin.pengeluaran*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                        <i data-lucide="trending-down" class="w-4 h-4"></i> Pengeluaran
                    </a>
                    <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ Route::is('admin.laporan*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} transition">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Laporan
                    </a>
                </nav>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 pt-4">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                </button>
            </form>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-40">
                <div class="relative w-96">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input type="text" placeholder="Cari data, kamar, atau penyewa..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center gap-4">
                    <button class="text-gray-500 p-1.5 hover:bg-gray-50 rounded-xl transition">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <button class="text-gray-500 p-1.5 hover:bg-gray-50 rounded-xl transition">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                    </button>
                    <div class="h-6 w-[1px] bg-gray-200 mx-1"></div>
                    <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-2xs text-white font-bold">A</div>
                        <span class="text-xs font-bold text-gray-700">Admin</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>