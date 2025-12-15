@extends('layouts.app')

@section('title', 'Dashboard Pemilik')
@section('page-title', 'Dashboard Pemilik')
@section('page-subtitle', 'Overview bisnis dan performa inventory')

@section('content')
<div class="space-y-6">
    <!-- Financial Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Nilai Aset -->
        <div class="rounded-3xl bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 text-white shadow-lg shadow-indigo-200">
            <div class="flex items-center justify-between mb-4">
                <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                    <i class="bi bi-currency-dollar text-2xl"></i>
                </div>
                <span class="text-xs font-medium bg-white/20 px-2 py-1 rounded-full">Total Aset</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</h3>
            <p class="text-indigo-100 text-sm">Nilai total inventory</p>
        </div>

        <!-- Total Jenis Barang -->
        <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="rounded-xl bg-indigo-50 p-3">
                    <i class="bi bi-box-seam text-2xl text-indigo-600"></i>
                </div>
                <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-full">Produk</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 mb-1">{{ $stats['total_barang'] }}</h3>
            <p class="text-slate-600 text-sm">Jenis barang aktif</p>
        </div>

        <!-- Transaksi Bulan Ini -->
        <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="rounded-xl bg-emerald-50 p-3">
                    <i class="bi bi-arrow-left-right text-2xl text-emerald-600"></i>
                </div>
                <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-full">Bulan Ini</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 mb-1">{{ $stats['monthly_transactions']['masuk'] + $stats['monthly_transactions']['keluar'] }}</h3>
            <p class="text-slate-600 text-sm">Total transaksi</p>
        </div>

        <!-- Alert Stok Rendah -->
        <div class="rounded-3xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 text-white shadow-lg shadow-amber-200">
            <div class="flex items-center justify-between mb-4">
                <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                    <i class="bi bi-exclamation-triangle text-2xl"></i>
                </div>
                <span class="text-xs font-medium bg-white/20 px-2 py-1 rounded-full">Perlu Perhatian</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $stats['low_stock_items'] }}</h3>
            <p class="text-amber-100 text-sm">Item stok rendah</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Charts & Trends -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Trends -->
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center">
                        <i class="bi bi-graph-up mr-2 text-indigo-500"></i>
                        Aktivitas Transaksi Bulanan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <!-- Barang Masuk -->
                        <div class="text-center p-6 rounded-2xl bg-emerald-50 border border-emerald-100">
                            <i class="bi bi-arrow-down-circle-fill text-4xl text-emerald-600 mb-3"></i>
                            <h4 class="text-3xl font-bold text-emerald-700 mb-1">{{ $stats['monthly_transactions']['masuk'] }}</h4>
                            <p class="text-emerald-600 text-sm font-medium">Barang Masuk</p>
                            <p class="text-xs text-emerald-500 mt-1">Total unit: {{ $stats['monthly_in_qty'] }}</p>
                        </div>

                        <!-- Barang Keluar -->
                        <div class="text-center p-6 rounded-2xl bg-rose-50 border border-rose-100">
                            <i class="bi bi-arrow-up-circle-fill text-4xl text-rose-600 mb-3"></i>
                            <h4 class="text-3xl font-bold text-rose-700 mb-1">{{ $stats['monthly_transactions']['keluar'] }}</h4>
                            <p class="text-rose-600 text-sm font-medium">Barang Keluar</p>
                            <p class="text-xs text-rose-500 mt-1">Total unit: {{ $stats['monthly_out_qty'] }}</p>
                        </div>
                    </div>

                    <!-- Net Movement -->
                    <div class="rounded-xl bg-slate-50 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 mb-1">Net Movement Bulan Ini</p>
                            <h4 class="text-2xl font-bold {{ ($stats['monthly_in_qty'] - $stats['monthly_out_qty']) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ ($stats['monthly_in_qty'] - $stats['monthly_out_qty']) >= 0 ? '+' : '' }}{{ number_format($stats['monthly_in_qty'] - $stats['monthly_out_qty']) }} Unit
                            </h4>
                        </div>
                        <i class="bi bi-{{ ($stats['monthly_in_qty'] - $stats['monthly_out_qty']) >= 0 ? 'graph-up' : 'graph-down' }} text-4xl {{ ($stats['monthly_in_qty'] - $stats['monthly_out_qty']) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}"></i>
                    </div>
                </div>
            </div>

            <!-- Top 5 Items by Value -->
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center">
                        <i class="bi bi-star-fill mr-2 text-indigo-500"></i>
                        Top 5 Item Berdasarkan Nilai
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($stats['top_items'] as $index => $item)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-bold text-sm">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ $item->nama_barang }}</h4>
                                        <p class="text-xs text-slate-500">{{ $item->kode_barang }} • Stok: {{ number_format($item->stok) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-slate-900">Rp {{ number_format($item->stok * $item->harga, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500">@ Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 py-8">Belum ada data barang</p>
                        @endforelse
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('reports.stock') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            Lihat Semua Barang <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Quick Actions & Alerts -->
        <div class="space-y-6">
            <!-- Quick Access -->
            <div class="rounded-3xl bg-gradient-to-br from-violet-500 to-purple-600 p-6 text-white shadow-lg shadow-violet-200">
                <div class="mb-4">
                    <i class="bi bi-speedometer2 text-3xl mb-3"></i>
                    <h3 class="font-bold text-lg">Akses Cepat</h3>
                    <p class="text-violet-100 text-sm">Menu yang sering diakses</p>
                </div>
                <div class="space-y-2">
                    <a href="{{ route('reports.stock') }}" class="block w-full rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm px-4 py-3 text-sm font-medium transition-all">
                        <i class="bi bi-file-earmark-bar-graph mr-2"></i> Laporan Stok
                    </a>
                    <a href="{{ route('reports.mutation') }}" class="block w-full rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm px-4 py-3 text-sm font-medium transition-all">
                        <i class="bi bi-arrow-left-right mr-2"></i> Mutasi Stok
                    </a>
                    <a href="{{ route('inventory.barang.index') }}" class="block w-full rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm px-4 py-3 text-sm font-medium transition-all">
                        <i class="bi bi-box-seam mr-2"></i> Data Barang
                    </a>
                    <a href="{{ route('reports.index') }}" class="block w-full rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm px-4 py-3 text-sm font-medium transition-all">
                        <i class="bi bi-bar-chart mr-2"></i> Semua Laporan
                    </a>
                </div>
            </div>

            <!-- Low Stock Alert -->
            @if($stats['low_stock_items'] > 0)
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                <div class="border-b border-slate-100 bg-amber-50/50 px-6 py-4">
                    <h3 class="text-base font-bold text-amber-900 flex items-center">
                        <i class="bi bi-exclamation-triangle-fill mr-2 text-amber-600"></i>
                        Item Stok Rendah
                    </h3>
                </div>
                <div class="p-4 max-h-96 overflow-y-auto">
                    <div class="space-y-3">
                        @foreach($stats['low_stock_list'] as $item)
                            <div class="p-3 rounded-xl bg-amber-50 border border-amber-100">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-sm text-slate-900">{{ $item->nama_barang }}</h4>
                                    <span class="text-xs px-2 py-1 rounded-full bg-amber-200 text-amber-900 font-bold">
                                        {{ $item->stok }}/{{ $item->min_stok }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600">{{ $item->kode_barang }}</p>
                                <div class="mt-2 h-1.5 bg-amber-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ ($item->stok / max($item->min_stok, 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('reports.stock') }}?filter=low" class="block text-center mt-4 text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Lihat Semua <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endif

            <!-- Working Hours Widget -->
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                @include('components.working-hours-widget')
            </div>
        </div>
    </div>
</div>
@endsection
