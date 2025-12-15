@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')
@section('page-title', 'Tambah Barang Keluar')
@section('page-subtitle', 'Catat transaksi pengeluaran barang')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Form Transaksi Keluar</h1>
        <a href="{{ route('inventory.barang-keluar.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6 sm:p-8">
        @if($barangs->count() == 0)
            <div class="rounded-xl bg-amber-50 border border-amber-100 p-6 text-center">
                <div class="inline-flex rounded-full bg-amber-100 p-3 mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-amber-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-amber-900 mb-2">Stok Tidak Tersedia</h3>
                <p class="text-amber-700 mb-6">Tidak ada barang dengan stok tersedia saat ini. Silakan input barang masuk terlebih dahulu.</p>
                <a href="{{ route('inventory.barang-masuk.create') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-600 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    <i class="bi bi-plus-circle mr-2"></i>
                    Input Barang Masuk
                </a>
            </div>
        @else
            <form action="{{ route('inventory.barang-keluar.store') }}" method="POST">
                @csrf
                
                <div class="space-y-8">
                    <!-- Transaction Info -->
                    <div>
                        <h3 class="text-base font-bold text-indigo-900 border-b border-indigo-100 pb-3 mb-6 flex items-center">
                            <i class="bi bi-box-seam mr-2 text-indigo-500"></i>
                            Detail Barang & Tujuan
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Barang Select -->
                            <div class="sm:col-span-2">
                                <label for="barang_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    Pilih Barang <span class="text-rose-500">*</span>
                                </label>
                                <select name="barang_id" 
                                        id="barang_id" 
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('barang_id') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                                        required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok Tersedia: {{ $barang->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-slate-500">Hanya menampilkan barang yang memiliki stok > 0.</p>
                                @error('barang_id')
                                    <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tujuan -->
                            <div class="sm:col-span-2">
                                <label for="tujuan" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    Tujuan Pengiriman <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-geo-alt text-slate-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="tujuan" 
                                           id="tujuan" 
                                           value="{{ old('tujuan') }}"
                                           class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('tujuan') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                                           placeholder="Contoh: Bpk. Santoso (Surabaya), Toko Cabang A, dll" 
                                           required>
                                </div>
                                @error('tujuan')
                                    <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Date Info -->
                    <div>
                        <h3 class="text-base font-bold text-indigo-900 border-b border-indigo-100 pb-3 mb-6 flex items-center">
                            <i class="bi bi-calendar-event mr-2 text-indigo-500"></i>
                            Kuantitas & Waktu
                        </h3>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    Tanggal Keluar <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal" 
                                       id="tanggal" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                       required>
                                @error('tanggal')
                                    <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label for="jumlah" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    Jumlah Keluar <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" 
                                       name="jumlah" 
                                       id="jumlah" 
                                       value="{{ old('jumlah', 1) }}"
                                       min="1"
                                       class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                       required>
                                @error('jumlah')
                                    <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div class="sm:col-span-2">
                                <label for="keterangan" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    Keterangan Tambahan
                                </label>
                                <textarea name="keterangan" 
                                          id="keterangan" 
                                          rows="3" 
                                          class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                          placeholder="Catatan pengiriman, nomor resi, dll...">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Box -->
                    <div class="rounded-xl bg-orange-50 border border-orange-100 p-4 flex items-start">
                        <i class="bi bi-exclamation-triangle-fill text-orange-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-bold text-orange-900">Perhatian Pengurangan Stok</h4>
                            <p class="text-xs text-orange-700 mt-1">Stok barang akan otomatis <strong>berkurang</strong> setelah data disimpan. Pastikan jumlah yang diinput tidak melebihi stok yang tersedia.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3 justify-end">
                        <a href="{{ route('inventory.barang-keluar.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 transition-all duration-200 hover:bg-rose-700 hover:shadow-rose-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 w-full sm:w-auto">
                            <i class="bi bi-box-arrow-up mr-2"></i>
                            Simpan Barang Keluar
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
