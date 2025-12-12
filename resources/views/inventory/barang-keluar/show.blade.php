@extends('layouts.app')

@section('title', 'Detail Barang Keluar')
@section('page-title', 'Detail Barang Keluar')
@section('page-subtitle', 'Informasi transaksi barang keluar')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Detail Transaksi Barang Keluar
                    </h5>
                    <div>
                        <a href="{{ route('inventory.barang-keluar.edit', $barangKeluar) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('inventory.barang-keluar.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%" class="text-muted"><strong>ID Transaksi</strong></td>
                        <td><code>#{{ $barangKeluar->id }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Tanggal</strong></td>
                        <td>{{ $barangKeluar->tanggal->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Barang</strong></td>
                        <td>
                            <strong>{{ $barangKeluar->barang->nama_barang }}</strong><br>
                            <small class="text-muted">Kode: <code>{{ $barangKeluar->barang->kode_barang }}</code></small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Jumlah Keluar</strong></td>
                        <td>
                            <span class="badge bg-danger fs-6">-{{ number_format($barangKeluar->jumlah) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Tujuan</strong></td>
                        <td>{{ $barangKeluar->tujuan }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Dicatat Oleh</strong></td>
                        <td>
                            {{ $barangKeluar->user->name }} ({{ $barangKeluar->user->role->label() }})<br>
                            <small class="text-muted">Username: {{ $barangKeluar->user->username }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Waktu Pencatatan</strong></td>
                        <td>{{ $barangKeluar->created_at->format('d F Y, H:i:s') }}</td>
                    </tr>
                    @if($barangKeluar->created_at != $barangKeluar->updated_at)
                        <tr>
                            <td class="text-muted"><strong>Terakhir Diubah</strong></td>
                            <td>{{ $barangKeluar->updated_at->format('d F Y, H:i:s') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                    <i class="bi bi-box-seam me-2"></i>
                    Info Barang Terkait
                </h6>
            </div>
            <div class="card-body">
                <h6>{{ $barangKeluar->barang->nama_barang }}</h6>
                <p class="mb-2"><small class="text-muted">{{ $barangKeluar->barang->kode_barang }}</small></p>
                
                <div class="mb-3">
                    <small class="text-muted">Stok Saat Ini:</small>
                    <h4 class="mb-0 text-{{ $barangKeluar->barang->stok > 0 ? 'success' : 'danger' }}">
                        {{ number_format($barangKeluar->barang->stok) }}
                    </h4>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Harga:</small>
                    <p class="mb-0">Rp {{ number_format($barangKeluar->barang->harga, 0, ',', '.') }}</p>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Minimum Stok:</small>
                    <p class="mb-0">{{ number_format($barangKeluar->barang->min_stok) }}</p>
                </div>

                <div class="mt-3">
                    @if($barangKeluar->barang->stok == 0)
                        <span class="badge bg-danger w-100">
                            <i class="bi bi-x-circle me-1"></i>
                            Stok Habis
                        </span>
                    @elseif($barangKeluar->barang->isBelowMinStock())
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
