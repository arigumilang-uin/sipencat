@extends('layouts.app')

@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')
@section('page-subtitle', 'Manajemen stok dan inventory')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-4">
        <div class="card text-white bg-success">
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

    <div class="col-md-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Stok Habis</h6>
                        <h2 class="mb-0">{{ $stats['out_of_stock_items'] }}</h2>
                    </div>
                    <i class="bi bi-x-circle-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Working Hours Widget -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        @include('components.working-hours-widget')
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Informasi Cepat
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Selamat datang, {{ auth()->user()->name }}!</strong></p>
                <p class="mb-0 text-muted">Anda sedang mengelola sistem inventory. Pastikan semua transaksi tercatat dengan benar.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Barang Masuk -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-box-arrow-in-down me-2"></i>
                    Barang Masuk Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($stats['recent_barang_masuk'] as $item)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $item->barang->nama_barang }}</strong>
                                <span class="badge bg-success">+{{ $item->jumlah }}</span>
                            </div>
                            <small class="text-muted">{{ $item->tanggal->format('d/m/Y') }} - {{ $item->supplier->nama_supplier }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            Belum ada transaksi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Barang Keluar -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-box-arrow-up me-2"></i>
                    Barang Keluar Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($stats['recent_barang_keluar'] as $item)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $item->barang->nama_barang }}</strong>
                                <span class="badge bg-danger">-{{ $item->jumlah }}</span>
                            </div>
                            <small class="text-muted">{{ $item->tanggal->format('d/m/Y') }} - {{ $item->tujuan }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            Belum ada transaksi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
