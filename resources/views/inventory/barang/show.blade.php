@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')
@section('page-subtitle', 'Informasi lengkap data inventaris')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Detail Item</h1>
        <a href="{{ route('inventory.barang.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Card Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-8">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="inline-flex items-center rounded-md bg-white/20 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-white/30 backdrop-blur-sm mb-3">
                                {{ $barang->kode_barang }}
                            </span>
                            <h2 class="text-2xl font-bold text-white">{{ $barang->nama_barang }}</h2>
                            <p class="text-indigo-100 mt-1">{{ $barang->kategori ?? 'Tanpa Kategori' }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white text-3xl">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Stok Tersedia</span>
                            <div class="flex items-center gap-3">
                                <span class="text-3xl font-bold text-slate-900">{{ $barang->stok }}</span>
                                @if($barang->isBelowMinStock())
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <i class="bi bi-exclamation-triangle-fill mr-1"></i> Stok Rendah
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        <i class="bi bi-check-circle-fill mr-1"></i> Aman
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Minimum stok: {{ $barang->min_stok }} unit</p>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Harga Satuan</span>
                            <div class="flex items-baseline">
                                <span class="text-xs font-medium text-slate-500 mr-1">Rp</span>
                                <span class="text-2xl font-bold text-slate-900">{{ number_format($barang->harga, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Nilai total aset: Rp {{ number_format($barang->stok * $barang->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <h4 class="text-sm font-bold text-slate-900 mb-4">Status Persediaan</h4>
                        <!-- Progress Bar for Stock Level relative to some arbitrary 'safe' level or just visual fluff -->
                        <div class="relative">
                            <div class="flex mb-2 items-center justify-between">
                                <div class="text-xs font-semibold inline-block text-indigo-600">
                                    {{ round(($barang->stok / max($barang->min_stok * 2, $barang->stok, 1)) * 100) }}% Kapasitas Aman (Estimasi)
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-100">
                                <div style="width:{{ min(100, ($barang->stok / max($barang->min_stok * 2, $barang->stok, 1)) * 100) }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500 transition-all duration-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions Column -->
        <div class="space-y-6">
            <div class="rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('inventory.barang.transactions', $barang) }}" class="flex items-center justify-center w-full rounded-xl bg-violet-50 px-4 py-3 text-sm font-bold text-violet-700 hover:bg-violet-100 transition-colors">
                        <i class="bi bi-clock-history mr-2"></i> Lihat Riwayat Transaksi
                    </a>

                    <a href="{{ route('inventory.barang.edit', $barang) }}" class="flex items-center justify-center w-full rounded-xl bg-amber-50 px-4 py-3 text-sm font-bold text-amber-700 hover:bg-amber-100 transition-colors">
                        <i class="bi bi-pencil-square mr-2"></i> Edit Barang
                    </a>
                    
                    <a href="{{ route('inventory.barang-masuk.create', ['barang_id' => $barang->id]) }}" class="flex items-center justify-center w-full rounded-xl bg-indigo-50 px-4 py-3 text-sm font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
                        <i class="bi bi-box-arrow-in-down mr-2"></i> Input Barang Masuk
                    </a>

                    <a href="{{ route('inventory.barang-keluar.create', ['barang_id' => $barang->id]) }}" class="flex items-center justify-center w-full rounded-xl bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                        <i class="bi bi-box-arrow-up mr-2"></i> Input Barang Keluar
                    </a>
                </div>
            </div>

            <div class="rounded-3xl bg-slate-50 ring-1 ring-slate-200/60 p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Informasi Sistem</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-xs text-slate-500">Dibuat pada</dt>
                        <dd class="text-xs font-medium text-slate-700">{{ $barang->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-xs text-slate-500">Terakhir update</dt>
                        <dd class="text-xs font-medium text-slate-700">{{ $barang->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
