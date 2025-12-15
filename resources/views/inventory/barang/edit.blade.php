@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')
@section('page-subtitle', 'Perbarui informasi detail barang')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Edit Data Barang</h1>
        <a href="{{ route('inventory.barang.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6 sm:p-8">
        <form action="{{ route('inventory.barang.update', $barang) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Data Barang Section -->
                <div>
                    <h3 class="text-base font-bold text-indigo-900 border-b border-indigo-100 pb-3 mb-6 flex items-center">
                        <i class="bi bi-box-seam mr-2 text-indigo-500"></i>
                        Informasi Barang
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Kode Barang -->
                        <div class="sm:col-span-2">
                            <label for="kode_barang" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Kode Barang <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="kode_barang" 
                                   id="kode_barang" 
                                   value="{{ old('kode_barang', $barang->kode_barang) }}"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('kode_barang') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                                   placeholder="Contoh: BRG-001" 
                                   required>
                            @error('kode_barang')
                                <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Barang -->
                        <div class="sm:col-span-2">
                            <label for="nama_barang" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Nama Barang <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="bi bi-tag text-slate-400"></i>
                                </div>
                                <input type="text" 
                                   name="nama_barang" 
                                   id="nama_barang" 
                                   value="{{ old('nama_barang', $barang->nama_barang) }}"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('nama_barang') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                                   placeholder="Contoh: Laptop Acer Nitro 5" 
                                   required>
                            </div>
                            @error('nama_barang')
                                <p class="mt-1 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori (Optional) -->
                        <div class="sm:col-span-2">
                            <label for="kategori" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Kategori
                            </label>
                            <input type="text" 
                                   name="kategori" 
                                   id="kategori" 
                                   value="{{ old('kategori', $barang->kategori ?? '') }}"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                   placeholder="Elektronik, Furniture, dll">
                        </div>
                    </div>
                </div>

                <!-- Stok & Harga Section -->
                <div>
                    <h3 class="text-base font-bold text-indigo-900 border-b border-indigo-100 pb-3 mb-6 flex items-center">
                        <i class="bi bi-sliders mr-2 text-indigo-500"></i>
                        Stok & Harga
                    </h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <!-- Stok Awal -->
                        <div>
                            <label for="stok" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Stok Saat Ini <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   name="stok" 
                                   id="stok" 
                                   value="{{ old('stok', $barang->stok) }}"
                                   min="0"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                   required>
                            <p class="mt-1 text-[10px] text-slate-500">Perubahan manual stok tidak tercatat di riwayat transaksi.</p>
                        </div>

                        <!-- Harga Satuan -->
                        <div>
                            <label for="harga" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Harga Satuan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 text-sm font-bold">Rp</span>
                                <input type="number" 
                                   name="harga" 
                                   id="harga" 
                                   value="{{ old('harga', $barang->harga) }}"
                                   min="0"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                   required>
                            </div>
                        </div>

                        <!-- Minimum Stok -->
                        <div>
                            <label for="min_stok" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Minimum Stok <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   name="min_stok" 
                                   id="min_stok" 
                                   value="{{ old('min_stok', $barang->min_stok) }}"
                                   min="0"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('inventory.barang.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-600 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="bi bi-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
