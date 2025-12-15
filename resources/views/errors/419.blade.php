@extends('errors::layout')

@section('title', 'Sesi Kedaluwarsa')
@section('code', '419')
@section('icon', 'bi-clock-history')
@section('gradient', 'from-amber-500 to-orange-600')

@section('message-title', 'Sesi Anda Telah Kedaluwarsa')
@section('message-body')
    Untuk keamanan, sesi Anda telah habis masa aktifnya. Halaman akan dialihkan secara otomatis, atau klik tombol di bawah untuk melanjutkan.
@endsection

@section('additional-content')
    <div class="rounded-xl bg-amber-50 border border-amber-100 p-4 max-w-md mx-auto">
        <div class="flex items-start">
            <i class="bi bi-shield-check text-amber-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-amber-800 text-left">
                <p class="font-medium mb-1">Kenapa ini terjadi?</p>
                <p class="text-xs">Sistem melindungi data Anda dengan membatasi waktu sesi. Ini terjadi jika form dibuka terlalu lama sebelum di-submit, atau Anda tidak aktif dalam waktu yang cukup lama.</p>
            </div>
        </div>
    </div>

    {{-- Auto-redirect script --}}
    <script>
        // Auto redirect after 3 seconds
        let countdown = 3;
        const redirectUrl = @json(auth()->check() ? route('dashboard') : route('login'));
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(timer);
                // Clear browser history to prevent back button loop
                window.location.replace(redirectUrl);
            }
        }, 1000);
    </script>
@endsection

@php
    $hideHomeButton = true;
@endphp

@section('action-buttons')
    <div class="text-center mb-4">
        <p class="text-sm text-slate-600 mb-2">
            <i class="bi bi-hourglass-split animate-pulse text-amber-500"></i>
            Mengalihkan dalam <span id="countdown" class="font-bold text-amber-600">3</span> detik...
        </p>
    </div>

    @auth
        {{-- User sudah login, redirect ke dashboard --}}
        <a href="{{ route('dashboard') }}" onclick="event.preventDefault(); window.location.replace(this.href);" class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-700 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="bi bi-house-door-fill mr-2"></i>
            Lanjut ke Dashboard
        </a>
    @else
        {{-- User belum login, redirect ke login --}}
        <a href="{{ route('login') }}" onclick="event.preventDefault(); window.location.replace(this.href);" class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-700 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="bi bi-box-arrow-in-right mr-2"></i>
            Login Kembali
        </a>
    @endauth

    {{-- Optional: Clear cache & retry --}}
    <button onclick="clearCacheAndRetry()" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
        <i class="bi bi-arrow-clockwise mr-2"></i>
        Coba Lagi dari Awal
    </button>

    <script>
        function clearCacheAndRetry() {
            // Clear the POST request from history
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
            
            // Redirect to fresh page
            @auth
                window.location.replace('{{ route('dashboard') }}');
            @else
                window.location.replace('{{ route('login') }}');
            @endauth
        }
    </script>
@endsection
