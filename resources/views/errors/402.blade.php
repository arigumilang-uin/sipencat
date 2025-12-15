@extends('errors::layout')

@section('title', 'Pembayaran Diperlukan')
@section('code', '402')
@section('icon', 'bi-credit-card-2-front')
@section('gradient', 'from-emerald-500 to-teal-600')

@section('message-title', 'Akses Berbayar Diperlukan')
@section('message-body')
    Fitur atau layanan yang Anda coba akses memerlukan pembayaran atau langganan aktif.
@endsection

@section('additional-content')
    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4 max-w-md mx-auto">
        <div class="flex items-start">
            <i class="bi bi-info-circle-fill text-emerald-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-emerald-800 text-left">
                <p class="font-medium mb-1">Informasi:</p>
                <p class="text-xs">Hubungi administrator untuk informasi lebih lanjut mengenai paket langganan atau pembayaran yang diperlukan.</p>
            </div>
        </div>
    </div>
@endsection
