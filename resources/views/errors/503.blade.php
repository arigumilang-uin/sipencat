@extends('errors::layout')

@section('title', 'Layanan Sedang Tidak Tersedia')
@section('code', '503')
@section('icon', 'bi-cone-striped')
@section('gradient', 'from-slate-500 to-slate-700')

@section('message-title', 'Sistem Sedang dalam Pemeliharaan')
@section('message-body')
    Maaf atas ketidaknyamanannya. Kami sedang melakukan pemeliharaan sistem untuk memberikan layanan yang lebih baik kepada Anda.
@endsection

@section('additional-content')
    <div class="rounded-xl bg-slate-100 border border-slate-200 p-6 max-w-md mx-auto">
        <div class="text-center">
            <i class="bi bi-gear-fill text-4xl text-slate-400 mb-3 animate-spin" style="animation-duration: 3s;"></i>
            <p class="text-sm text-slate-700 font-medium mb-2">Pemeliharaan Terjadwal</p>
            <p class="text-xs text-slate-600">Sistem akan kembali online sebentar lagi. Terima kasih atas pengertian Anda.</p>
        </div>
    </div>
    
    <div class="rounded-xl bg-indigo-50 border border-indigo-100 p-4 max-w-md mx-auto mt-4">
        <div class="flex items-start">
            <i class="bi bi-info-circle-fill text-indigo-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-indigo-800 text-left">
                <p class="font-medium mb-1">Yang sedang kami lakukan:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Update sistem dan keamanan</li>
                    <li>Optimalisasi performa database</li>
                    <li>Perbaikan dan peningkatan fitur</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@php
    $hideHomeButton = true;
@endphp

@section('action-buttons')
    <button onclick="location.reload()" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        <i class="bi bi-arrow-clockwise mr-2"></i>
        Periksa Status Sistem
    </button>
@endsection
