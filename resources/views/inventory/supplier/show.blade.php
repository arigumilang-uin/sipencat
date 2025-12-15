@extends('layouts.app')

@section('title', 'Detail Supplier')
@section('page-title', 'Detail Supplier')
@section('page-subtitle', 'Informasi lengkap mitra supplier')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Detail Supplier</h1>
        <a href="{{ route('inventory.supplier.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="relative bg-slate-800 px-6 py-12 text-center">
            <div class="absolute inset-0 overflow-hidden opacity-20">
                <i class="bi bi-building text-9xl absolute -right-4 -bottom-8 text-white"></i>
            </div>
            <div class="relative flex flex-col items-center">
                <div class="h-20 w-20 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white text-4xl mb-4 ring-1 ring-white/20">
                    <span class="font-bold">{{ substr($supplier->nama_supplier, 0, 1) }}</span>
                </div>
                <h2 class="text-2xl font-bold text-white">{{ $supplier->nama_supplier }}</h2>
                <span class="mt-2 inline-flex items-center rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-medium text-emerald-200 ring-1 ring-inset ring-emerald-500/30">
                    Active Supplier
                </span>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 gap-6">
                <div class="flex items-start p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-colors hover:bg-indigo-50/50 hover:border-indigo-100 group">
                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform duration-200">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-slate-900">Kontak Telepon</h3>
                        <p class="text-sm text-slate-600 mt-1 font-mono hover:text-indigo-600">{{ $supplier->telp }}</p>
                    </div>
                </div>

                <div class="flex items-start p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-colors hover:bg-indigo-50/50 hover:border-indigo-100 group">
                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform duration-200">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-slate-900">Alamat Lengkap</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $supplier->alamat }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('inventory.supplier.edit', $supplier) }}" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-amber-200 transition-all duration-200 hover:bg-amber-600 hover:shadow-amber-300 hover:-translate-y-0.5">
                    <i class="bi bi-pencil-square mr-2"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
