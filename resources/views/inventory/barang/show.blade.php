@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Barang</h5>
                    <div>
                        <a href="{{ route('inventory.barang.edit', $barang) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('inventory.barang.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Kode Barang</strong></td>
                        <td><code>{{ $barang->kode_barang }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Barang</strong></td>
                        <td>{{ $barang->nama_barang }}</td>
                    </tr>
                    <tr>
                        <td><strong>Stok</strong></td>
                        <td><span class="badge bg-{{ $barang->stok > 0 ? 'success' : 'danger' }}">{{ $barang->stok }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Harga</strong></td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Minimum Stok</strong></td>
                        <td>{{ $barang->min_stok }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($barang->isBelowMinStock())
                                <span class="badge bg-warning">Stok Rendah</span>
                            @else
                                <span class="badge bg-success">Stok Normal</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
