@extends('layouts.app')

@section('title', 'Laporan Supplier')
@section('page-title', 'Laporan per Supplier')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h5><i class="bi bi-shop"></i> Laporan per Supplier</h5>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        @if($suppliers->count())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th class="text-center">Total Transaksi</th>
                        <th class="text-center">Total Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $s->nama_supplier }}</strong></td>
                            <td>{{ $s->telp }}</td>
                            <td class="text-center"><span class="badge bg-primary">{{ $s->barang_masuk_count }}</span></td>
                            <td class="text-center"><span class="badge bg-success">{{ number_format($s->barang_masuk_sum_jumlah ?? 0) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $suppliers->links() }}
        @else
            <p class="text-center text-muted">Tidak ada data</p>
        @endif
    </div>
</div>
@endsection
