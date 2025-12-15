@extends('errors::layout')

@section('title', 'Akses Ditolak')
@section('code', '401')
@section('icon', 'bi-shield-lock-fill')
@section('gradient', 'from-rose-500 to-pink-600')

@section('message-title', 'Anda Belum Masuk ke Sistem')
@section('message-body')
    Halaman yang Anda coba akses memerlukan autentikasi. Silakan login terlebih dahulu untuk melanjutkan.
@endsection

@section('action-buttons')
    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 transition-all duration-200 hover:bg-rose-700 hover:shadow-rose-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
        <i class="bi bi-box-arrow-in-right mr-2"></i>
        Login Sekarang
    </a>
@endsection
