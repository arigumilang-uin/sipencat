@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Dashboard laporan sistem inventory')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-purple-700 shadow-lg mb-8">
    <div class="absolute inset-0 opacity-10">
        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
        </svg>
    </div>
    <div class="relative px-6 py-8 sm:px-12 sm:py-10 text-white">
        <h2 class="text-3xl font-bold tracking-tight mb-2 flex items-center">
            <i class="bi bi-file-earmark-bar-graph text-white mr-3"></i>
            Pusat Laporan SIPENCAT
        </h2>
        <p class="text-indigo-100 text-lg max-w-2xl">
            Akses berbagai laporan komprehensif untuk monitoring performa, analisis stok, dan audit inventory bisnis Anda.
        </p>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <!-- Total Barang -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-t-4 border-indigo-500">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-50 rounded-md p-3">
                    <i class="bi bi-box-seam text-2xl text-indigo-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Barang</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_barang']) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Masuk -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-t-4 border-emerald-500">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-emerald-50 rounded-md p-3">
                    <i class="bi bi-box-arrow-in-down text-2xl text-emerald-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Masuk</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_barang_masuk']) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Keluar -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-t-4 border-rose-500">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-rose-50 rounded-md p-3">
                    <i class="bi bi-box-arrow-up text-2xl text-rose-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Keluar</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_barang_keluar']) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Supplier -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-t-4 border-cyan-500">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-cyan-50 rounded-md p-3">
                    <i class="bi bi-shop text-2xl text-cyan-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Supplier</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_suppliers']) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Menu Grid -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @can('canManageInventory')
        {{-- Admin & Gudang: Operational Reports --}}
        
        <!-- Stok -->
        <a href="{{ route('reports.stock') }}" class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:-translate-y-1">
            <div class="flex items-center">
                <span class="inline-flex rounded-lg p-3 bg-indigo-50 text-indigo-700 ring-4 ring-white group-hover:bg-indigo-100 transition-colors">
                    <i class="bi bi-graph-up-arrow text-2xl"></i>
                </span>
                <div class="ml-4">
                    <h3 class="text-lg font-medium">Laporan Stok Barang</h3>
                    <p class="mt-1 text-sm text-gray-500">Monitoring stok, nilai aset, dan barang limit.</p>
                </div>
                <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-indigo-400" aria-hidden="true">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                    </svg>
                </span>
            </div>
        </a>

        <!-- Mutasi -->
        <a href="{{ route('reports.mutation') }}" class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-amber-500 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:-translate-y-1">
            <div class="flex items-center">
                <span class="inline-flex rounded-lg p-3 bg-amber-50 text-amber-700 ring-4 ring-white group-hover:bg-amber-100 transition-colors">
                    <i class="bi bi-arrow-left-right text-2xl"></i>
                </span>
                <div class="ml-4">
                    <h3 class="text-lg font-medium">Laporan Mutasi Stok</h3>
                    <p class="mt-1 text-sm text-gray-500">Pergerakan stok masuk, keluar, dan penyesuaian.</p>
                </div>
            </div>
        </a>

        <!-- Barang Masuk -->
        <a href="{{ route('reports.barang-masuk') }}" class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-emerald-500 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:-translate-y-1">
            <div class="flex items-center">
                <span class="inline-flex rounded-lg p-3 bg-emerald-50 text-emerald-700 ring-4 ring-white group-hover:bg-emerald-100 transition-colors">
                    <i class="bi bi-box-arrow-in-down text-2xl"></i>
                </span>
                <div class="ml-4">
                    <h3 class="text-lg font-medium">Laporan Barang Masuk</h3>
                    <p class="mt-1 text-sm text-gray-500">Riwayat transaksi penerimaan barang dari supplier.</p>
                </div>
            </div>
        </a>

        <!-- Barang Keluar -->
        <a href="{{ route('reports.barang-keluar') }}" class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-rose-500 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:-translate-y-1">
            <div class="flex items-center">
                <span class="inline-flex rounded-lg p-3 bg-rose-50 text-rose-700 ring-4 ring-white group-hover:bg-rose-100 transition-colors">
                    <i class="bi bi-box-arrow-up text-2xl"></i>
                </span>
                <div class="ml-4">
                    <h3 class="text-lg font-medium">Laporan Barang Keluar</h3>
                    <p class="mt-1 text-sm text-gray-500">Riwayat pengeluaran barang ke berbagai tujuan.</p>
                </div>
            </div>
        </a>

        <!-- Supplier (Strategic) -->
        @can('canViewSupplierReport')
            <a href="{{ route('reports.supplier') }}" class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-cyan-500 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:-translate-y-1 col-span-1 lg:col-span-2">
                <div class="flex items-center">
                    <span class="inline-flex rounded-lg p-3 bg-cyan-50 text-cyan-700 ring-4 ring-white group-hover:bg-cyan-100 transition-colors">
                        <i class="bi bi-shop text-2xl"></i>
                    </span>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium">Laporan per Supplier</h3>
                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Strategic</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Analisis kinerja supplier, frekuensi supply, dan kontribusi terhadap total inventory.</p>
                    </div>
                </div>
            </a>
        @endcan
    @endcan

    @can('isPemilik')
        {{-- Pemilik: Business Overview Reports Only --}}
        <a href="{{ route('reports.stock') }}" class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:-translate-y-1">
            <div class="flex items-center">
                <span class="inline-flex rounded-lg p-3 bg-indigo-50 text-indigo-700 ring-4 ring-white group-hover:bg-indigo-100 transition-colors">
                    <i class="bi bi-graph-up-arrow text-2xl"></i>
                </span>
                <div class="ml-4">
                    <h3 class="text-lg font-medium">Laporan Stok Barang</h3>
                    <p class="mt-1 text-sm text-gray-500">Overview performa stok dan nilai aset.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.supplier') }}" class="group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-cyan-500 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:-translate-y-1">
            <div class="flex items-center">
                <span class="inline-flex rounded-lg p-3 bg-cyan-50 text-cyan-700 ring-4 ring-white group-hover:bg-cyan-100 transition-colors">
                    <i class="bi bi-shop text-2xl"></i>
                </span>
                <div class="ml-4">
                    <h3 class="text-lg font-medium">Laporan Supplier</h3>
                    <p class="mt-1 text-sm text-gray-500">Analisis kemitraan dan supply chain.</p>
                </div>
            </div>
        </a>
    @endcan
</div>

@can('isPemilik')
    <div class="mt-8 rounded-md bg-blue-50 p-4 border border-blue-100">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="bi bi-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3 flex-1 md:flex md:justify-between">
                <p class="text-sm text-blue-700"><strong>Info untuk Pemilik:</strong> Anda memiliki akses view-only untuk monitoring bisnis. Hubungi staff gudang untuk input transaksi.</p>
            </div>
        </div>
    </div>
@endcan
@endsection
