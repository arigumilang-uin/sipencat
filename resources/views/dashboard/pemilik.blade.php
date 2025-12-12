@extends('layouts.app')

@section('title', 'Dashboard Pemilik')
@section('page-title', 'Dashboard Pemilik')
@section('page-subtitle', 'Laporan dan overview bisnis')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Total Barang</h6>
                        <h2 class="mb-0">{{ $stats['total_barang'] }}</h2>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Nilai Total Stok</h6>
                        <h2 class="mb-0">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</h2>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Stok Rendah</h6>
                        <h2 class="mb-0">{{ $stats['low_stock_items'] }}</h2>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Monthly Transactions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-month me-2"></i>
                    Transaksi Bulan Ini
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <i class="bi bi-arrow-down-circle text-success fs-1"></i>
                            <h3 class="mt-2 mb-0">{{ $stats['monthly_transactions']['masuk'] }}</h3>
                            <small class="text-muted">Barang Masuk</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                            <i class="bi bi-arrow-up-circle text-danger fs-1"></i>
                            <h3 class="mt-2 mb-0">{{ $stats['monthly_transactions']['keluar'] }}</h3>
                            <small class="text-muted">Barang Keluar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Link -->
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body text-center p-5">
                <i class="bi bi-graph-up fs-1 mb-3"></i>
                <h4>Lihat Laporan Lengkap</h4>
                <p class="mb-4">Akses laporan inventory, transaksi, dan analisis bisnis</p>
                <a href="{{ route('reports.index') }}" class="btn btn-light">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Buka Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
