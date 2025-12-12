@extends('layouts.app')

@section('title', 'Detail Supplier')
@section('page-title', 'Detail Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>Detail Supplier</h5>
                    <div>
                        <a href="{{ route('inventory.supplier.edit', $supplier) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="{{ route('inventory.supplier.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Nama Supplier</strong></td>
                        <td>{{ $supplier->nama_supplier }}</td>
                    </tr>
                    <tr>
                        <td><strong>Telepon</strong></td>
                        <td>{{ $supplier->telp }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>{{ $supplier->alamat }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
