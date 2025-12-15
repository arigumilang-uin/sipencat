@extends('errors::layout')

@section('title', 'Halaman Tidak Ditemukan')
@section('code', '404')
@section('icon', 'bi-search')
@section('gradient', 'from-blue-500 to-indigo-600')

@section('message-title', 'Halaman yang Anda Cari Tidak Ditemukan')
@section('message-body')
    Maaf, halaman yang Anda tuju tidak tersedia. Mungkin URL salah diketik, atau halaman telah dipindahkan atau dihapus.
@endsection

@section('additional-content')
    <div class="rounded-xl bg-blue-50 border border-blue-100 p-4 max-w-md mx-auto">
        <div class="flex items-start">
            <i class="bi bi-lightbulb-fill text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-800 text-left">
                <p class="font-medium mb-1">Saran:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Periksa kembali alamat URL yang Anda masukkan</li>
                    <li>Gunakan menu navigasi untuk menemukan halaman</li>
                    <li>Kembali ke halaman sebelumnya dan coba lagi</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
