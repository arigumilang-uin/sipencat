@extends('layouts.app')

@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')
@section('page-subtitle', 'Manajemen stok dan inventory')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
    <!-- Total Barang -->
    <div class="relative overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
        <dt>
            <div class="absolute rounded-md bg-emerald-500 p-3">
                <i class="bi bi-box-seam text-white text-xl"></i>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Total Barang</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_barang'] }}</p>
        </dd>
    </div>

    <!-- Stok Rendah -->
    <div class="relative overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
        <dt>
            <div class="absolute rounded-md bg-amber-500 p-3">
                <i class="bi bi-exclamation-triangle-fill text-white text-xl"></i>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Stok Rendah</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
            <p class="text-2xl font-semibold text-gray-900">{{ $stats['low_stock_items'] }}</p>
            @if($stats['low_stock_items'] > 0)
                <span class="ml-2 text-xs font-medium text-amber-600">Perlu restock</span>
            @endif
        </dd>
    </div>

    <!-- Stok Habis -->
    <div class="relative overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
        <dt>
            <div class="absolute rounded-md bg-red-500 p-3">
                <i class="bi bi-x-circle-fill text-white text-xl"></i>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Stok Habis</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
            <p class="text-2xl font-semibold text-gray-900">{{ $stats['out_of_stock_items'] }}</p>
            @if($stats['out_of_stock_items'] > 0)
                <span class="ml-2 text-xs font-medium text-red-600">Kritis!</span>
            @endif
        </dd>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-1">
        @include('components.working-hours-widget')
    </div>
    
    <div class="lg:col-span-2">
        <div class="rounded-lg bg-white shadow-sm border border-gray-100 h-full">
            <div class="border-b border-gray-200 px-4 py-4 sm:px-6 bg-gray-50/50">
                <h3 class="text-base font-semibold leading-6 text-gray-900">
                    <i class="bi bi-info-circle mr-2 text-blue-500"></i>
                    Informasi Cepat
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <p class="text-lg font-medium text-gray-900 mb-2">Selamat datang, {{ auth()->user()->name }}!</p>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Anda sedang login sebagai Staff Gudang. Sistem ini akan mencatat waktu mulai dan selesai kerja Anda secara otomatis.
                    Pastikan untuk selalu mencatat barang masuk dan keluar dengan teliti.
                </p>
                <div class="mt-4 flex gap-3">
                    <a href="{{ route('inventory.barang-masuk.index') }}" class="inline-flex items-center rounded-md bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 transition-colors">
                        <i class="bi bi-box-arrow-in-down mr-2"></i> Input Barang Masuk
                    </a>
                    <a href="{{ route('inventory.barang-keluar.index') }}" class="inline-flex items-center rounded-md bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100 transition-colors">
                        <i class="bi bi-box-arrow-up mr-2"></i> Input Barang Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Recent Barang Masuk -->
    <div class="rounded-lg bg-white shadow-sm border border-gray-100">
        <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900">
                <i class="bi bi-box-arrow-in-down mr-2 text-emerald-500"></i>
                Barang Masuk Terbaru
            </h3>
        </div>
        <ul role="list" class="divide-y divide-gray-100">
            @forelse($stats['recent_barang_masuk'] as $item)
                <li class="flex items-center justify-between gap-x-6 py-4 px-4 hover:bg-gray-50 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-start gap-x-3">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $item->barang->nama_barang }}</p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                            <p class="whitespace-nowrap">{{ $item->tanggal->format('d/m/Y') }}</p>
                            <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current"><circle cx="1" cy="1" r="1" /></svg>
                            <p class="truncate">{{ $item->supplier->nama_supplier }}</p>
                        </div>
                    </div>
                    <div class="flex flex-none items-center gap-x-4">
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            +{{ $item->jumlah }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="py-8 text-center text-sm text-gray-500 italic">
                    Belum ada transaksi barang masuk.
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Recent Barang Keluar -->
    <div class="rounded-lg bg-white shadow-sm border border-gray-100">
        <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900">
                <i class="bi bi-box-arrow-up mr-2 text-rose-500"></i>
                Barang Keluar Terbaru
            </h3>
        </div>
        <ul role="list" class="divide-y divide-gray-100">
            @forelse($stats['recent_barang_keluar'] as $item)
                <li class="flex items-center justify-between gap-x-6 py-4 px-4 hover:bg-gray-50 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-start gap-x-3">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $item->barang->nama_barang }}</p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                            <p class="whitespace-nowrap">{{ $item->tanggal->format('d/m/Y') }}</p>
                            <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current"><circle cx="1" cy="1" r="1" /></svg>
                            <p class="truncate">{{ $item->tujuan }}</p>
                        </div>
                    </div>
                    <div class="flex flex-none items-center gap-x-4">
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                            -{{ $item->jumlah }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="py-8 text-center text-sm text-gray-500 italic">
                    Belum ada transaksi barang keluar.
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
