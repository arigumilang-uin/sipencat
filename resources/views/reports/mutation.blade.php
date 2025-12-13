@extends('layouts.app')

@section('title', 'Laporan Mutasi Stok')
@section('page-title', 'Laporan Mutasi Stok')
@section('page-subtitle', 'Pergerakan stok barang per periode')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Mutasi Stok</h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form action="{{ route('reports.mutation') }}" method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
                </div>

                <!-- Table -->
                @if($barangs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" width="5%" class="align-middle text-center">No</th>
                                    <th rowspan="2" width="12%" class="align-middle">Kode</th>
                                    <th rowspan="2" width="23%" class="align-middle">Nama Barang</th>
                                    <th colspan="4" class="text-center">Stok</th>
                                </tr>
                                <tr>
                                    <th width="12%" class="text-center">Awal</th>
                                    <th width="12%" class="text-center">Masuk</th>
                                    <th width="12%" class="text-center">Keluar</th>
                                    <th width="12%" class="text-center">Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangs as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td><code>{{ $item['kode'] }}</code></td>
                                        <td><strong>{{ $item['nama'] }}</strong></td>
                                        <td class="text-center">{{ number_format($item['stok_awal']) }}</td>
                                        <td class="text-center text-success">
                                            @if($item['masuk'] > 0)
                                                <strong>+{{ number_format($item['masuk']) }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center text-danger">
                                            @if($item['keluar'] > 0)
                                                <strong>-{{ number_format($item['keluar']) }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $item['stok_akhir'] > 0 ? 'success' : 'danger' }} fs-6">
                                                {{ number_format($item['stok_akhir']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-center">{{ number_format($barangs->sum('stok_awal')) }}</th>
                                    <th class="text-center text-success">+{{ number_format($barangs->sum('masuk')) }}</th>
                                    <th class="text-center text-danger">-{{ number_format($barangs->sum('keluar')) }}</th>
                                    <th class="text-center">{{ number_format($barangs->sum('stok_akhir')) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Tidak ada data untuk periode ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
