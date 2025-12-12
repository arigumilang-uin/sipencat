@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5><i class="bi bi-shop"></i> Daftar Supplier</h5>
                    <a href="{{ route('inventory.supplier.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Supplier
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($suppliers->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $supplier->nama_supplier }}</strong></td>
                                    <td>{{ $supplier->telp }}</td>
                                    <td>{{ Str::limit($supplier->alamat, 50) }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('inventory.supplier.show', $supplier) }}" class="btn btn-info"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('inventory.supplier.edit', $supplier) }}" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                                            @can('isAdmin')
                                                <form action="{{ route('inventory.supplier.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $suppliers->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted">Belum ada data supplier</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
