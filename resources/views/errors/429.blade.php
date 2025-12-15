@extends('errors::layout')

@section('title', 'Terlalu Banyak Permintaan')
@section('code', '429')
@section('icon', 'bi-hourglass-split')
@section('gradient', 'from-purple-500 to-pink-600')

@section('message-title', 'Permintaan Terlalu Cepat')
@section('message-body')
    Anda telah melakukan terlalu banyak permintaan dalam waktu singkat. Mohon tunggu sebentar sebelum mencoba lagi.
@endsection

@section('additional-content')
    <div class="rounded-xl bg-purple-50 border border-purple-100 p-4 max-w-md mx-auto">
        <div class="flex items-start">
            <i class="bi bi-shield text-purple-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-purple-800 text-left">
                <p class="font-medium mb-1">Perlindungan Rate Limit</p>
                <p class="text-xs">Sistem membatasi jumlah permintaan untuk mencegah penyalahgunaan dan menjaga performa server. Tunggu beberapa saat dan coba lagi.</p>
            </div>
        </div>
    </div>
@endsection

@section('action-buttons')
    <button onclick="setTimeout(() => location.reload(), 5000); this.innerHTML='<i class=\'bi bi-hourglass-split mr-2\'></i>Memuat ulang dalam 5 detik...'; this.disabled=true;" class="inline-flex items-center justify-center rounded-xl bg-purple-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-purple-200 transition-all duration-200 hover:bg-purple-700 hover:shadow-purple-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
        <i class="bi bi-arrow-clockwise mr-2"></i>
        Coba Lagi
    </button>
@endsection
