@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')
@section('page-title', 'Tambah Barang Keluar')
@section('page-subtitle', 'Input transaksi barang keluar')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-box-arrow-up me-2"></i>
                        Form Barang Keluar
                    </h5>
                    <a href="{{ route('inventory.barang-keluar.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($barangs->count() == 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Tidak ada barang dengan stok tersedia. Silakan input barang masuk terlebih dahulu.
                    </div>
                @else
                    <form action="{{ route('inventory.barang-keluar.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="barang_id" class="form-label fw-bold">
                                Barang <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('barang_id') is-invalid @enderror" 
                                    id="barang_id" 
                                    name="barang_id" 
                                    required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label fw-bold">
                                        Jumlah <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('jumlah') is-invalid @enderror" 
                                           id="jumlah" 
                                           name="jumlah" 
                                           value="{{ old('jumlah', 1) }}"
                                           min="1"
                                           required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label fw-bold">
                                        Tanggal <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('tanggal') is-invalid @enderror" 
                                           id="tanggal" 
                                           name="tanggal" 
                                           value="{{ old('tanggal', date('Y-m-d')) }}"
                                           required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tujuan" class="form-label fw-bold">
                                Tujuan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('tujuan') is-invalid @enderror" 
                                   id="tujuan" 
                                   name="tujuan" 
                                   value="{{ old('tujuan') }}"
                                   placeholder="Contoh: Cabang Jakarta, Penjualan, dll"
                                   required>
                            @error('tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Catatan:</strong> Stok barang akan otomatis berkurang setelah data disimpan. Pastikan jumlah yang diinput tidak melebihi stok tersedia.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-save me-1"></i>
                                Simpan Barang Keluar
                            </button>
                            <a href="{{ route('inventory.barang-keluar.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
