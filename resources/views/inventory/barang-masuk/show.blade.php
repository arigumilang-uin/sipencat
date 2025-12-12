@extends('layouts.app')

@section('title', 'Detail Barang Masuk')
@section('page-title', 'Detail Barang Masuk')
@section('page-subtitle', 'Informasi transaksi barang masuk')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Detail Transaksi Barang Masuk
                    </h5>
                    <div>
                        <a href="{{ route('inventory.barang-masuk.edit', $barangMasuk) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('inventory.barang-masuk.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%" class="text-muted"><strong>ID Transaksi</strong></td>
                        <td><code>#{{ $barangMasuk->id }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Tanggal</strong></td>
                        <td>{{ $barangMasuk->tanggal->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Barang</strong></td>
                        <td>
                            <strong>{{ $barangMasuk->barang->nama_barang }}</strong><br>
                            <small class="text-muted">Kode: <code>{{ $barangMasuk->barang->kode_barang }}</code></small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Supplier</strong></td>
                        <td>
                            {{ $barangMasuk->supplier->nama_supplier }}<br>
                            <small class="text-muted">{{ $barangMasuk->supplier->telp }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Jumlah Masuk</strong></td>
                        <td>
                            <span class="badge bg-success fs-6">+{{ number_format($barangMasuk->jumlah) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Keterangan</strong></td>
                        <td>{{ $barangMasuk->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Dicatat Oleh</strong></td>
                        <td>
                            {{ $barangMasuk->user->name }} ({{ $barangMasuk->user->role->label() }})<br>
                            <small class="text-muted">Username: {{ $barangMasuk->user->username }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Waktu Pencatatan</strong></td>
                        <td>{{ $barangMasuk->created_at->format('d F Y, H:i:s') }}</td>
                    </tr>
                    @if($barangMasuk->created_at != $barangMasuk->updated_at)
                        <tr>
                            <td class="text-muted"><strong>Terakhir Diubah</strong></td>
                            <td>{{ $barangMasuk->updated_at->format('d F Y, H:i:s') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-box-seam me-2"></i>
                    Info Barang Terkait
                </h6>
            </div>
            <div class="card-body">
                <h6>{{ $barangMasuk->barang->nama_barang }}</h6>
                <p class="mb-2"><small class="text-muted">{{ $barangMasuk->barang->kode_barang }}</small></p>
                
                <div class="mb-3">
                    <small class="text-muted">Stok Saat Ini:</small>
                    <h4 class="mb-0 text-success">{{ number_format($barangMasuk->barang->stok) }}</h4>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Harga:</small>
                    <p class="mb-0">Rp {{ number_format($barangMasuk->barang->harga, 0, ',', '.') }}</p>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Minimum Stok:</small>
                    <p class="mb-0">{{ number_format($barangMasuk->barang->min_stok) }}</p>
                </div>

                <div class="mt-3">
                    @if($barangMasuk->barang->isBelowMinStock())
                        <span class="badge bg-warning w-100">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Stok Rendah
                        </span>
                    @else
                        <span class="badge bg-success w-100">
                            <i class="bi bi-check-circle me-1"></i>
                            Stok Normal
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
