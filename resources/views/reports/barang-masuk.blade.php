@extends('layouts.app')

@section('title', 'Laporan Barang Masuk')
@section('page-title', 'Laporan Barang Masuk')
@section('page-subtitle', 'Rekapitulasi penerimaan barang dari supplier')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Barang Masuk</h1>
            <p class="mt-1 text-sm text-slate-500">Laporan detail transaksi penerimaan barang inventory.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all duration-200">
            <i class="bi bi-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Transaksi -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-emerald-100 group">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-emerald-500/10 blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-receipt text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Transaksi</p>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ number_format($summary['total_transactions']) }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Item -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-indigo-100 group">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-indigo-500/10 blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-box-seam text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Unit Masuk</p>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ number_format($summary['total_quantity']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <!-- Filter -->
        <div class="border-b border-slate-100 p-6 bg-slate-50/50">
            <form action="{{ route('reports.barang-masuk') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Mulai Tanggal</label>
                    <input type="date" name="start_date" class="block w-full rounded-xl border-slate-200 bg-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="block w-full rounded-xl border-slate-200 bg-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200" value="{{ request('end_date') }}">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Supplier</label>
                    <select name="supplier_id" class="block w-full rounded-xl border-slate-200 bg-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="bi bi-filter mr-2"></i>
                        Filter Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        @if($transactions->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-indigo-50/20 text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Barang</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Supplier</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Jumlah</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Penerima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($transactions as $t)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-600">
                                    <span class="font-bold">{{ $t->tanggal->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-700">{{ $t->barang->nama_barang }}</span>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $t->barang->kode_barang }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $t->supplier->nama_supplier }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        +{{ number_format($t->jumlah) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            {{ substr($t->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs text-slate-500">{{ $t->user->name }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-white px-6 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center text-slate-500">
                <div class="rounded-full bg-slate-50 p-4 mb-4 ring-1 ring-slate-100">
                    <i class="bi bi-inbox text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Tidak ada data transaksi</h3>
                <p class="text-sm mt-1">Coba sesuaikan filter pencarian periode tanggal Anda.</p>
            </div>
        @endif
    </div>
</div>
@endsection
