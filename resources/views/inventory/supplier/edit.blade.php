@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')
@section('page-subtitle', 'Perbarui informasi data supplier')

@section('content')
<div class="max-w-xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Edit Data Supplier</h1>
        <a href="{{ route('inventory.supplier.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6 sm:p-8">
        <form action="{{ route('inventory.supplier.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Nama Supplier -->
                <div>
                    <label for="nama_supplier" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Nama Supplier <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-building text-slate-400"></i>
                        </div>
                        <input type="text" 
                               name="nama_supplier" 
                               id="nama_supplier" 
                               value="{{ old('nama_supplier', $supplier->nama_supplier) }}"
                               class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('nama_supplier') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                               placeholder="Contoh: PT. Sumber Makmur" 
                               required>
                    </div>
                    @error('nama_supplier')
                        <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label for="telp" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Telepon / Kontak <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-telephone text-slate-400"></i>
                        </div>
                        <input type="text" 
                               name="telp" 
                               id="telp" 
                               value="{{ old('telp', $supplier->telp) }}"
                               class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('telp') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                               placeholder="Contoh: 0812-3456-7890" 
                               required>
                    </div>
                    @error('telp')
                        <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Alamat Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="alamat" 
                              id="alamat" 
                              rows="4" 
                              class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('alamat') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                              placeholder="Alamat kantor atau gudang supplier" 
                              required>{{ old('alamat', $supplier->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('inventory.supplier.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
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
