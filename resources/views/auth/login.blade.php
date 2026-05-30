@extends('layouts/app')

@section('title', 'Login - KOSKITAA')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white border border-gray-200 rounded-2xl p-8 shadow-xl animate-fade-in">
        
        <!-- HEADER -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-blue-600 tracking-tight">KOSKITAA</h1>
            <p class="text-sm text-gray-500 mt-1">Silakan login untuk mengakses akun Anda</p>
        </div>

        <!-- NOTIFIKASI ERROR JIKA GAGAL LOGIN -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
                <ul class="list-disc list-inside font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM LOGIN SATU PINTU -->
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off" placeholder="Masukkan email terdaftar"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 px-4 py-3 rounded-xl text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 px-4 py-3 rounded-xl text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-md shadow-blue-600/10">
                Masuk Sistem
            </button>
        </form>

        <!-- LINK REGISTER UNTUK PENGHUNI BARU -->
        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">
                Belum punya akun kos? 
                <a href="{{ route('penghuni.register') }}" class="text-blue-600 font-bold hover:underline">Daftar Di Sini</a>
            </p>
        </div>

    </div>
</div>
@endsection