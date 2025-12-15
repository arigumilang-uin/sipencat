@extends('errors::layout')

@section('title', 'Akses Ditolak')
@section('code', '403')
@section('icon', 'bi-shield-exclamation')
@section('gradient', 'from-orange-500 to-red-600')

@section('message-title', 'Anda Tidak Memiliki Izin Akses')
@section('message-body')
    Maaf, Anda tidak memiliki permission yang diperlukan untuk mengakses halaman atau fitur ini. Hubungi administrator jika Anda merasa ini adalah kesalahan.
@endsection

@section('additional-content')
    <div class="rounded-xl bg-orange-50 border border-orange-100 p-4 max-w-md mx-auto">
        <div class="flex items-start">
            <i class="bi bi-info-circle-fill text-orange-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-orange-800 text-left">
                <p class="font-medium mb-1">Kemungkinan Penyebab:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Role atau jabatan Anda tidak memiliki akses ke fitur ini</li>
                    <li>Halaman ini hanya untuk Admin atau role tertentu</li>
                    <li>Akun Anda mungkin sedang dibatasi</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
