@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Dashboard laporan sistem inventory')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <h4><i class="bi bi-file-earmark-bar-graph me-2"></i>Pusat Laporan SIPENCAT</h4>
                <p class="mb-0">Akses berbagai laporan untuk monitoring dan analisis bisnis inventory</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-box-seam fs-1 text-primary"></i>
                <h3 class="mt-2">{{ number_format($stats['total_barang']) }}</h3>
                <p class="text-muted mb-0">Total Barang</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-box-arrow-in-down fs-1 text-success"></i>
                <h3 class="mt-2">{{ number_format($stats['total_barang_masuk']) }}</h3>
                <p class="text-muted mb-0">Total Barang Masuk</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <i class="bi bi-box-arrow-up fs-1 text-danger"></i>
                <h3 class="mt-2">{{ number_format($stats['total_barang_keluar']) }}</h3>
                <p class="text-muted mb-0">Total Barang Keluar</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-shop fs-1 text-info"></i>
                <h3 class="mt-2">{{ number_format($stats['total_suppliers']) }}</h3>
                <p class="text-muted mb-0">Total Supplier</p>
            </div>
        </div>
    </div>
</div>

<!-- Report Menu -->
<div class="row">
    @can('canManageInventory')
        {{-- Admin & Gudang: Operational Reports --}}
        <div class="col-md-6 mb-3">
            <a href="{{ route('reports.stock') }}" class="text-decoration-none">
                <div class="card h-100 border-start border-primary border-4 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-graph-up-arrow fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Laporan Stok Barang</h5>
                                <p class="card-text text-muted mb-0">Monitoring stok saat ini, barang dengan stok rendah, dan nilai inventory</p>
                            </div>
                            <i class="bi bi-chevron-right fs-4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-3">
            <a href="{{ route('reports.mutation') }}" class="text-decoration-none">
                <div class="card h-100 border-start border-warning border-4 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-arrow-left-right fs-1 text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Laporan Mutasi Stok</h5>
                                <p class="card-text text-muted mb-0">Pergerakan stok barang (masuk, keluar, dan mutasi) per periode</p>
                            </div>
                            <i class="bi bi-chevron-right fs-4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-3">
            <a href="{{ route('reports.barang-masuk') }}" class="text-decoration-none">
                <div class="card h-100 border-start border-success border-4 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-box-arrow-in-down fs-1 text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Laporan Barang Masuk</h5>
                                <p class="card-text text-muted mb-0">Riwayat transaksi barang masuk dari supplier per periode</p>
                            </div>
                            <i class="bi bi-chevron-right fs-4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-3">
            <a href="{{ route('reports.barang-keluar') }}" class="text-decoration-none">
                <div class="card h-100 border-start border-danger border-4 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-box-arrow-up fs-1 text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Laporan Barang Keluar</h5>
                                <p class="card-text text-muted mb-0">Riwayat transaksi barang keluar ke tujuan per periode</p>
                            </div>
                            <i class="bi bi-chevron-right fs-4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-3">
            <a href="{{ route('reports.supplier') }}" class="text-decoration-none">
                <div class="card h-100 border-start border-info border-4 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-shop fs-1 text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Laporan per Supplier</h5>
                                <p class="card-text text-muted mb-0">Statistik transaksi per supplier dan kontribusi masing-masing</p>
                            </div>
                            <i class="bi bi-chevron-right fs-4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endcan

    @can('isPemilik')
        {{-- Pemilik: Business Overview Reports Only --}}
        <div class="col-md-6 mb-3">
            <a href="{{ route('reports.stock') }}" class="text-decoration-none">
                <div class="card h-100 border-start border-primary border-4 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-graph-up-arrow fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Laporan Stok Barang</h5>
                                <p class="card-text text-muted mb-0">Overview stok inventory dan nilai aset barang</p>
                            </div>
                            <i class="bi bi-chevron-right fs-4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-3">
            <a href="{{ route('reports.supplier') }}" class="text-decoration-none">
                <div class="card h-100 border-start border-info border-4 hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-shop fs-1 text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Laporan per Supplier</h5>
                                <p class="card-text text-muted mb-0">Performance supplier dan kontribusi terhadap inventory</p>
                            </div>
                            <i class="bi bi-chevron-right fs-4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Info untuk Pemilik:</strong> Anda memiliki akses view-only untuk monitoring bisnis. 
                Untuk input transaksi, silakan hubungi staff gudang.
            </div>
        </div>
    @endcan
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush
