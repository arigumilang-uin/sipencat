@extends('layouts.app')

@section('title', 'Detail Barang Keluar')
@section('page-title', 'Detail Barang Keluar')
@section('page-subtitle', 'Informasi lengkap transaksi pengeluaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Detail Transaksi Keluar</h1>
        <a href="{{ route('inventory.barang-keluar.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Transaction Detail Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-base font-bold text-slate-900 flex items-center">
                        <i class="bi bi-file-text mr-2 text-indigo-500"></i>
                        Informasi Transaksi
                    </h3>
                    <div class="flex gap-2">
                        <a href="{{ route('inventory.barang-keluar.edit', $barangKeluar) }}" class="inline-flex items-center justify-center rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100 transition-colors">
                            <i class="bi bi-pencil-square mr-1.5"></i> Edit
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <table class="w-full text-sm text-left">
                        <tbody class="divide-y divide-slate-100">
                            <tr class="group">
                                <th class="py-3 text-xs font-bold uppercase tracking-wider text-slate-500 w-1/3">ID Transaksi</th>
                                <td class="py-3 font-mono text-slate-700">#{{ $barangKeluar->id }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Keluar</th>
                                <td class="py-3 font-bold text-slate-900">{{ $barangKeluar->tanggal->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 text-xs font-bold uppercase tracking-wider text-slate-500">Jumlah Keluar</th>
                                <td class="py-3">
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-sm font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                        -{{ number_format($barangKeluar->jumlah) }} Unit
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="py-3 text-xs font-bold uppercase tracking-wider text-slate-500 align-top pt-4">Item Barang</th>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 mr-3">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $barangKeluar->barang->nama_barang }}</div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $barangKeluar->barang->kode_barang }}</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="py-3 text-xs font-bold uppercase tracking-wider text-slate-500 align-top pt-4">Tujuan</th>
                                <td class="py-3 text-sm text-slate-700 font-medium">
                                    {{ $barangKeluar->tujuan }}
                                </td>
                            </tr>
                            <tr>
                                <th class="py-3 text-xs font-bold uppercase tracking-wider text-slate-500 align-top pt-4">Keterangan</th>
                                <td class="py-3 text-slate-600 italic">
                                    "{{ $barangKeluar->keterangan ?? 'Tidak ada keterangan tambahan' }}"
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-8 pt-6 border-t border-slate-100 grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs text-slate-500 mb-1">Dicatat Oleh</span>
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 mr-2">
                                    {{ substr($barangKeluar->user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ $barangKeluar->user->name }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 mb-1">Waktu Input</span>
                            <span class="text-sm font-medium text-slate-700">{{ $barangKeluar->created_at->format('d/m/Y H:i') }} WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Item Info Column -->
        <div class="space-y-6">
            <div class="rounded-3xl bg-rose-600 text-white shadow-lg shadow-rose-200 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
                
                <h3 class="text-sm font-bold uppercase tracking-wider text-rose-200 mb-4">Status Stok Barang</h3>
                
                <div class="flex items-center mb-6">
                    <h2 class="text-3xl font-bold">{{ number_format($barangKeluar->barang->stok) }}</h2>
                    <span class="ml-2 text-sm text-rose-200">Unit Tersedia</span>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm py-2 border-b border-rose-500/30">
                        <span class="text-rose-200">Harga Satuan</span>
                        <span class="font-bold">Rp {{ number_format($barangKeluar->barang->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm py-2 border-b border-rose-500/30">
                        <span class="text-rose-200">Min. Stok</span>
                        <span class="font-bold">{{ number_format($barangKeluar->barang->min_stok) }} Unit</span>
                    </div>
                </div>

                <div class="mt-6">
                    @if($barangKeluar->barang->stok == 0)
                        <div class="rounded-xl bg-slate-900/20 border border-slate-400/30 p-3 flex items-center">
                            <i class="bi bi-x-circle-fill text-slate-300 text-xl mr-3"></i>
                            <div>
                                <h4 class="text-sm font-bold text-slate-100">Stok Habis</h4>
                                <p class="text-xs text-slate-200/80">Tidak dapat melakukan transaksi keluar lagi.</p>
                            </div>
                        </div>
                    @elseif($barangKeluar->barang->isBelowMinStock())
                        <div class="rounded-xl bg-amber-500/20 border border-amber-400/30 p-3 flex items-center">
                            <i class="bi bi-exclamation-triangle-fill text-amber-300 text-xl mr-3"></i>
                            <div>
                                <h4 class="text-sm font-bold text-amber-100">Stok Menipis</h4>
                                <p class="text-xs text-amber-200/80">Segera lakukan re-stock.</p>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl bg-emerald-500/20 border border-emerald-400/30 p-3 flex items-center">
                            <i class="bi bi-check-circle-fill text-emerald-300 text-xl mr-3"></i>
                            <div>
                                <h4 class="text-sm font-bold text-emerald-100">Stok Aman</h4>
                                <p class="text-xs text-emerald-200/80">Persediaan mencukupi.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
