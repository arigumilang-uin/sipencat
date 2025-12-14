@extends('layouts.app')

@section('title', 'Laporan Supplier')
@section('page-title', 'Laporan Performa Supplier')
@section('page-subtitle', 'Analisis kinerja dan volume transaksi supplier')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Performa Supplier</h1>
            <p class="mt-1 text-sm text-slate-500">Monitoring aktivitas pengadaan barang dari supplier partner.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all duration-200">
            <i class="bi bi-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-shop text-indigo-500"></i>
                Daftar Supplier
            </h3>
            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                {{ $suppliers->total() }} Partner
            </span>
        </div>

        @if($suppliers->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-indigo-50/20 text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Supplier</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Kontak</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-center">Total Transaksi</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Total Barang Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($suppliers as $s)
                            <tr class="group hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs ring-2 ring-white shadow-sm">
                                            {{ substr($s->nama_supplier, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $s->nama_supplier }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center text-slate-500">
                                        <i class="bi bi-telephone text-xs mr-2"></i>
                                        <span class="font-mono text-xs">{{ $s->telp }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-100">
                                        {{ $s->barang_masuk_count }} Transaksi
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-100">
                                        {{ number_format($s->barang_masuk_sum_jumlah ?? 0) }} Unit
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-6 py-4 border-t border-slate-100">
                {{ $suppliers->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="rounded-full bg-slate-50 p-4 mb-4 ring-1 ring-slate-100">
                    <i class="bi bi-shop text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Belum ada data supplier</h3>
                <p class="text-sm text-slate-500 mt-1">Data supplier akan muncul setelah ada transaksi barang masuk.</p>
            </div>
        @endif
    </div>
</div>
@endsection
