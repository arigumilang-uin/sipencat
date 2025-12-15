@extends('errors::layout')

@section('title', 'Sesi Kedaluwarsa')
@section('code', '419')
@section('icon', 'bi-clock-history')
@section('gradient', 'from-amber-500 to-orange-600')

@section('message-title', 'Sesi Anda Telah Kedaluwarsa')
@section('message-body')
    Untuk keamanan, sesi Anda telah habis masa aktifnya. Silakan kembali ke dashboard atau login kembali untuk melanjutkan.
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
@endsection

@php
    $hideHomeButton = true;
@endphp

@section('action-buttons')
    @auth
        {{-- User sudah login, redirect ke dashboard --}}
        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-700 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="bi bi-house-door-fill mr-2"></i>
            Kembali ke Dashboard
        </a>
    @else
        {{-- User belum login, redirect ke login --}}
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-700 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <i class="bi bi-box-arrow-in-right mr-2"></i>
            Login untuk Melanjutkan
        </a>
    @endauth
@endsection
