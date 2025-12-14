@extends('layouts.app')

@section('title', 'Laporan Stok Barang')
@section('page-title', 'Laporan Stok Barang')
@section('page-subtitle', 'Monitoring stok dan nilai inventory')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Laporan Stok Barang</h1>
            <p class="mt-1 text-sm text-slate-500">Analisis komprehensif status stok dan valuasi inventory.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all duration-200">
            <i class="bi bi-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Items -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-indigo-100 group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-indigo-500/10 blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-box-seam text-xl"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Item</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($summary['total_items']) }}</h3>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-amber-100 group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-amber-500/10 blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-exclamation-triangle text-xl"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">Stok Rendah</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($summary['low_stock']) }}</h3>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-rose-100 group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-rose-500/10 blur-2xl group-hover:bg-rose-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-x-circle text-xl"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">Stok Habis</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($summary['out_of_stock']) }}</h3>
            </div>
        </div>

        <!-- Total Value -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-emerald-100 group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-emerald-500/10 blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-cash-stack text-xl"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">Nilai Total Aset</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($summary['total_stock_value'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <!-- Header & Filter -->
        <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-table text-indigo-500"></i>
                    Detail Stok Barang
                </h3>
                
                <form action="{{ route('reports.stock') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:flex-none">
                        <select name="status" class="block w-full rounded-xl border-slate-200 bg-white py-2 pl-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Stok Normal</option>
                            <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                            <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        @if($barangs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-indigo-50/20 text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Barang</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Stok</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Min Stok</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Harga Satuan</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Total Nilai</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($barangs as $barang)
                            <tr class="group hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-slate-400 font-medium">{{ $barangs->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $barang->nama_barang }}</span>
                                        <code class="text-[10px] text-slate-400 font-mono mt-0.5 uppercase tracking-wide bg-slate-100 inline-block px-1 rounded w-fit">{{ $barang->kode_barang }}</code>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold {{ $barang->stok == 0 ? 'text-rose-600' : ($barang->isBelowMinStock() ? 'text-amber-600' : 'text-slate-700') }}">
                                        {{ number_format($barang->stok) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-500">{{ number_format($barang->min_stok) }}</td>
                                <td class="px-6 py-4 text-right text-slate-600">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-slate-700">Rp {{ number_format($barang->stok * $barang->harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($barang->stok == 0)
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                            Habis
                                        </span>
                                    @elseif($barang->isBelowMinStock())
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            Rendah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            Normal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right font-bold text-slate-600 uppercase text-xs tracking-wider">Total Inventory Value:</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-700 text-sm">
                                Rp {{ number_format($barangs->sum(fn($b) => $b->stok * $b->harga), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="bg-white px-6 py-4 border-t border-slate-100">
                {{ $barangs->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="rounded-full bg-slate-50 p-4 mb-4 ring-1 ring-slate-100">
                    <i class="bi bi-inbox-fill text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Tidak ada data stok</h3>
                <p class="text-sm text-slate-500 mt-1">Coba sesuaikan filter status stok Anda.</p>
            </div>
        @endif
    </div>
</div>
@endsection
