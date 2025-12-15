@extends('layouts.app')

@section('title', 'Riwayat Transaksi - ' . $barang->nama_barang)
@section('page-title', 'Riwayat Transaksi')
@section('page-subtitle', 'Detail lengkap semua transaksi masuk dan keluar')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Riwayat Transaksi Barang</h1>
            <p class="text-sm text-slate-600 mt-1">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('inventory.barang.show', $barang) }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
                Kembali ke Detail
            </a>
            <a href="{{ route('inventory.barang.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="bi bi-list-ul mr-2"></i>
                Daftar Barang
            </a>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Stok Saat Ini</span>
                <span class="text-2xl font-bold text-slate-900">{{ number_format($barang->stok) }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Total Transaksi</span>
                <span class="text-2xl font-bold text-slate-900">{{ $transactions->count() }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Total Masuk</span>
                <span class="text-2xl font-bold text-emerald-600">+{{ number_format($transactions->where('type', 'masuk')->sum('jumlah')) }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Total Keluar</span>
                <span class="text-2xl font-bold text-rose-600">-{{ number_format($transactions->where('type', 'keluar')->sum('jumlah')) }}</span>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center">
                <i class="bi bi-clock-history mr-2 text-indigo-500"></i>
                Riwayat Lengkap ({{ $transactions->count() }} Transaksi)
            </h3>
        </div>

        @if($transactions->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex rounded-full bg-slate-100 p-4 mb-4">
                    <i class="bi bi-inbox text-slate-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Transaksi</h3>
                <p class="text-slate-600">Barang ini belum memiliki riwayat transaksi masuk atau keluar.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">No</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Jenis</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Jumlah</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Stok Sebelum</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Stok Sesudah</th>
                            <th class="px-6 py-4 font-bold text-slate-700">Partner/Tujuan</th>
                            <th class="px-6 py-4 font-bold text-slate-700">Keterangan</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Dicatat Oleh</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $index => $transaction)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-slate-700 whitespace-nowrap">
                                    <div class="font-medium">{{ $transaction['tanggal']->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $transaction['created_at']->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction['type'] === 'masuk')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            <i class="bi bi-arrow-down-circle mr-1"></i> Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                            <i class="bi bi-arrow-up-circle mr-1"></i> Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction['type'] === 'masuk')
                                        <span class="text-emerald-600 font-bold">+{{ number_format($transaction['jumlah']) }}</span>
                                    @else
                                        <span class="text-rose-600 font-bold">-{{ number_format($transaction['jumlah']) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium whitespace-nowrap">{{ number_format($transaction['stock_before']) }}</td>
                                <td class="px-6 py-4 text-slate-900 font-bold whitespace-nowrap">{{ number_format($transaction['stock_after']) }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-700">{{ $transaction['partner'] }}</div>
                                    @if($transaction['partner_detail'] !== '-')
                                        <div class="text-xs text-slate-500">{{ $transaction['partner_detail'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate" title="{{ $transaction['keterangan'] }}">
                                    {{ $transaction['keterangan'] }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 whitespace-nowrap">{{ $transaction['user'] }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($transaction['type'] === 'masuk')
                                        <a href="{{ route('inventory.barang-masuk.show', $transaction['id']) }}" 
                                           class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
                                            <i class="bi bi-eye mr-1"></i> Detail
                                        </a>
                                    @else
                                        <a href="{{ route('inventory.barang-keluar.show', $transaction['id']) }}" 
                                           class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
                                            <i class="bi bi-eye mr-1"></i> Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
