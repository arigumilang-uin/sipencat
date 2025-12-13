@extends('layouts.app')

@section('title', 'Laporan Barang Keluar')
@section('page-title', 'Laporan Barang Keluar')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h5><i class="bi bi-box-arrow-up"></i> Laporan Barang Keluar</h5>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Total Transaksi</h6>
                        <h3>{{ number_format($summary['total_transactions']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Total Jumlah</h6>
                        <h3>{{ number_format($summary['total_quantity']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('reports.barang-keluar') }}" method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4"><input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}"></div>
                <div class="col-md-4"><input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}"></div>
                <div class="col-md-4"><button class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i> Filter</button></div>
            </div>
        </form>

        @if($transactions->count())
            <table class="table table-striped table-sm">
                <thead><tr><th>Tgl</th><th>Barang</th><th>Tujuan</th><th class="text-end">Qty</th><th>User</th></tr></thead>
                <tbody>
                    @foreach($transactions as $t)
                        <tr>
                            <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $t->barang->nama_barang }}</td>
                            <td>{{ $t->tujuan }}</td>
                            <td class="text-end"><span class="badge bg-danger">-{{ $t->jumlah }}</span></td>
                            <td><small>{{ $t->user->name }}</small></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $transactions->links() }}
        @else
            <p class="text-center text-muted">Tidak ada data</p>
        @endif
    </div>
</div>
@endsection
