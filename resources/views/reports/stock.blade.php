@extends('layouts.app')

@section('title', 'Laporan Stok Barang')
@section('page-title', 'Laporan Stok Barang')
@section('page-subtitle', 'Monitoring stok dan nilai inventory')

@section('content')
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="mb-1">Total Item</h6>
                <h3 class="mb-0">{{ number_format($summary['total_items']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="mb-1">Stok Rendah</h6>
                <h3 class="mb-0">{{ number_format($summary['low_stock']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6 class="mb-1">Stok Habis</h6>
                <h3 class="mb-0">{{ number_format($summary['out_of_stock']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="mb-1">Nilai Total</h6>
                <h3 class="mb-0">Rp {{ number_format($summary['total_stock_value'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Daftar Stok Barang</h5>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form action="{{ route('reports.stock') }}" method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Stok Normal</option>
                        <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                        <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Table -->
        @if($barangs->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Kode</th>
                            <th width="25%">Nama Barang</th>
                            <th width="10%" class="text-end">Stok</th>
                            <th width="10%" class="text-end">Min Stok</th>
                            <th width="13%" class="text-end">Harga</th>
                            <th width=15%" class="text-end">Nilai Stok</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $barang)
                            <tr>
                                <td>{{ $barangs->firstItem() + $loop->index }}</td>
                                <td><code>{{ $barang->kode_barang }}</code></td>
                                <td><strong>{{ $barang->nama_barang }}</strong></td>
                                <td class="text-end">
                                    <span class="badge bg-{{ $barang->stok > 0 ? 'success' : 'danger' }}">
                                        {{ number_format($barang->stok) }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format($barang->min_stok) }}</td>
                                <td class="text-end">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($barang->stok * $barang->harga, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($barang->stok == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($barang->isBelowMinStock())
                                        <span class="badge bg-warning">Rendah</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="6" class="text-end">Total:</th>
                            <th class="text-end">
                                Rp {{ number_format($barangs->sum(fn($b) => $b->stok * $b->harga), 0, ',', '.') }}
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-3">
                {{ $barangs->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-3">Tidak ada data</p>
            </div>
        @endif
    </div>
</div>
@endsection
