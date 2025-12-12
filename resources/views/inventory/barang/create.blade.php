@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')
@section('page-subtitle', 'Tambah data barang baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Form Tambah Barang
                    </h5>
                    <a href="{{ route('inventory.barang.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('inventory.barang.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="kode_barang" class="form-label fw-bold">
                            Kode Barang <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('kode_barang') is-invalid @enderror" 
                               id="kode_barang" 
                               name="kode_barang" 
                               value="{{ old('kode_barang') }}"
                               placeholder="BRG001"
                               required>
                        @error('kode_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_barang" class="form-label fw-bold">
                            Nama Barang <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama_barang') is-invalid @enderror" 
                               id="nama_barang" 
                               name="nama_barang" 
                               value="{{ old('nama_barang') }}"
                               placeholder="Nama barang"
                               required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stok" class="form-label fw-bold">
                                    Stok Awal <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('stok') is-invalid @enderror" 
                                       id="stok" 
                                       name="stok" 
                                       value="{{ old('stok', 0) }}"
                                       min="0"
                                       required>
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="harga" class="form-label fw-bold">
                                    Harga <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('harga') is-invalid @enderror" 
                                       id="harga" 
                                       name="harga" 
                                       value="{{ old('harga', 0) }}"
                                       min="0"
                                       step="0.01"
                                       required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="min_stok" class="form-label fw-bold">
                                    Min. Stok <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('min_stok') is-invalid @enderror" 
                                       id="min_stok" 
                                       name="min_stok" 
                                       value="{{ old('min_stok', 10) }}"
                                       min="0"
                                       required>
                                @error('min_stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Simpan Barang
                        </button>
                        <a href="{{ route('inventory.barang.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
