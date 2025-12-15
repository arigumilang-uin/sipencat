@extends('errors::layout')

@section('title', 'Terjadi Kesalahan Server')
@section('code', '500')
@section('icon', 'bi-exclamation-octagon-fill')
@section('gradient', 'from-red-500 to-rose-600')

@section('message-title', 'Maaf, Terjadi Kesalahan pada Server')
@section('message-body')
    Terjadi kesalahan yang tidak terduga saat memproses permintaan Anda. Tim teknis kami telah menerima notifikasi dan akan segera menangani masalah ini.
@endsection

@section('additional-content')
    <div class="rounded-xl bg-red-50 border border-red-100 p-4 max-w-md mx-auto">
        <div class="flex items-start">
            <i class="bi bi-tools text-red-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-red-800 text-left">
                <p class="font-medium mb-1">Apa yang bisa Anda lakukan?</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Tunggu beberapa saat dan muat ulang halaman</li>
                    <li>Coba akses halaman lain terlebih dahulu</li>
                    <li>Jika masalah berlanjut, hubungi administrator</li>
                    <li>Catat waktu kejadian untuk laporan error</li>
                </ul>
            </div>
        </div>
    </div>
    
    @if(config('app.debug') && isset($exception))
    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 max-w-md mx-auto mt-4">
        <div class="text-xs text-slate-600 font-mono text-left">
            <p class="font-bold mb-2 text-slate-800">Debug Info (hanya terlihat saat development):</p>
            <p class="truncate">{{ get_class($exception) }}</p>
        </div>
    </div>
    @endif
@endsection

@section('action-buttons')
    <button onclick="location.reload()" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-200 transition-all duration-200 hover:bg-red-700 hover:shadow-red-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
        <i class="bi bi-arrow-clockwise mr-2"></i>
        Muat Ulang Halaman
    </button>
@endsection
