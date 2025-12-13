@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-500 via-purple-600 to-indigo-800">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-2xl">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600 mb-6">
                <i class="bi bi-shield-check text-4xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">SIPENCAT</h2>
            <p class="mt-2 text-sm text-gray-500">
                Sistem Pengamanan & Catatan Aset Terpadu
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('login.attempt') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-person text-gray-400"></i>
                        </div>
                        <input id="username" name="username" type="text" 
                            required 
                            autofocus
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-all duration-200" 
                            placeholder="Masukkan username Anda"
                            value="{{ old('username') }}">
                    </div>
                    @error('username')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" 
                            required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-all duration-200" 
                            placeholder="Masukkan password Anda">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 shadow-lg shadow-indigo-500/30">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="bi bi-box-arrow-in-right text-indigo-400 group-hover:text-indigo-300 text-lg"></i>
                    </span>
                    Masuk ke Sistem
                </button>
            </div>
        </form>
        
        <div class="text-center mt-6">
            <p class="text-xs text-gray-400">© {{ date('Y') }} SIPENCAT. Sistem Keamanan Informasi 5A.</p>
        </div>
    </div>
</div>
@endsection
