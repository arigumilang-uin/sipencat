@extends('layouts.app')

@section('title', 'Laporan Mutasi Stok')
@section('page-title', 'Laporan Mutasi Stok')
@section('page-subtitle', 'Pergerakan stok barang per periode')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Mutasi Stok</h1>
            <p class="mt-1 text-sm text-slate-500">Analisis pergerakan inventory (awal, masuk, keluar, akhir).</p>
        </div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all duration-200">
            <i class="bi bi-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <!-- Filter & Info -->
        <div class="border-b border-slate-100 p-6 bg-slate-50/50">
            <div class="flex flex-col gap-6">
                <!-- Info Alert -->
                <div class="rounded-xl bg-indigo-50 border border-indigo-100 p-4 flex items-start gap-3">
                    <i class="bi bi-info-circle-fill text-indigo-500 mt-0.5"></i>
                    <div class="text-sm text-indigo-900">
                        <span class="font-bold">Periode Laporan:</span> 
                        {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} <span class="mx-1 text-indigo-400">s/d</span> {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                    </div>
                </div>

                <!-- Filter Form -->
                <form action="{{ route('reports.mutation') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" class="block w-full rounded-xl border-slate-200 bg-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200" value="{{ $startDate }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="block w-full rounded-xl border-slate-200 bg-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200" value="{{ $endDate }}" required>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="bi bi-search mr-2"></i>
                            Tampilkan Data
                        </button>
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
                            <th scope="col" rowspan="2" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider border-b border-r border-indigo-100 w-16 text-center">No</th>
                            <th scope="col" rowspan="2" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider border-b border-indigo-100">Barang</th>
                            <th scope="col" colspan="4" class="px-6 py-2 font-bold uppercase text-[11px] tracking-wider text-center border-b border-indigo-100 bg-indigo-100/30">Pergerakan Stok</th>
                        </tr>
                        <tr>
                            <th scope="col" class="px-4 py-2 font-bold uppercase text-[10px] tracking-wider text-center border-b border-indigo-100">Awal</th>
                            <th scope="col" class="px-4 py-2 font-bold uppercase text-[10px] tracking-wider text-center border-b border-indigo-100 text-emerald-600">Masuk</th>
                            <th scope="col" class="px-4 py-2 font-bold uppercase text-[10px] tracking-wider text-center border-b border-indigo-100 text-rose-600">Keluar</th>
                            <th scope="col" class="px-4 py-2 font-bold uppercase text-[10px] tracking-wider text-center border-b border-indigo-100 bg-indigo-50/50">Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($barangs as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-center text-slate-400 font-medium border-r border-slate-50">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">{{ $item['nama'] }}</span>
                                        <code class="text-[10px] text-slate-400 font-mono mt-0.5 bg-slate-100 px-1 py-0.5 rounded w-fit">{{ $item['kode'] }}</code>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center font-mono text-slate-600">
                                    {{ number_format($item['stok_awal']) }}
                                </td>
                                <td class="px-4 py-4 text-center font-mono font-bold text-emerald-600 bg-emerald-50/30">
                                    @if($item['masuk'] > 0)
                                        +{{ number_format($item['masuk']) }}
                                    @else
                                        <span class="text-slate-300 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center font-mono font-bold text-rose-600 bg-rose-50/30">
                                    @if($item['keluar'] > 0)
                                        -{{ number_format($item['keluar']) }}
                                    @else
                                        <span class="text-slate-300 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center font-mono font-bold text-slate-800 bg-indigo-50/30">
                                    <span class="inline-flex items-center justify-center rounded-lg px-2.5 py-1 text-xs {{ $item['stok_akhir'] > 0 ? 'bg-white text-slate-700 shadow-sm border border-slate-200' : 'bg-rose-100 text-rose-700' }}">
                                        {{ number_format($item['stok_akhir']) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-right font-bold text-slate-600 uppercase text-xs tracking-wider">Total Pergerakan:</td>
                            <td class="px-4 py-4 text-center font-bold text-slate-500 font-mono">{{ number_format($barangs->sum('stok_awal')) }}</td>
                            <td class="px-4 py-4 text-center font-bold text-emerald-600 font-mono bg-emerald-50/50">+{{ number_format($barangs->sum('masuk')) }}</td>
                            <td class="px-4 py-4 text-center font-bold text-rose-600 font-mono bg-rose-50/50">-{{ number_format($barangs->sum('keluar')) }}</td>
                            <td class="px-4 py-4 text-center font-bold text-indigo-700 font-mono bg-indigo-50/50">{{ number_format($barangs->sum('stok_akhir')) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center text-slate-500">
                <div class="rounded-full bg-slate-50 p-4 mb-4 ring-1 ring-slate-100">
                    <i class="bi bi-arrow-left-right text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Tidak ada mutasi</h3>
                <p class="text-sm mt-1">Belum ada pergerakan barang pada periode ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
