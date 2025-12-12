@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')
@section('page-subtitle', 'Manajemen master data barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Daftar Barang
                    </h5>
                    <a href="{{ route('inventory.barang.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Barang
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($barangs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Kode</th>
                                    <th width="25%">Nama Barang</th>
                                    <th width="10%"  class="text-end">Stok</th>
                                    <th width="15%" class="text-end">Harga</th>
                                    <th width="10%" class="text-end">Min Stok</th>
                                    <th width="8%">Status</th>
                                    <th width="15%" class="text-center">Aksi</th>
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
                                        <td class="text-end">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($barang->min_stok) }}</td>
                                        <td>
                                            @if($barang->isBelowMinStock())
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Low
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>OK
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('inventory.barang.show', $barang) }}" 
                                                   class="btn btn-info" 
                                                   title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('inventory.barang.edit', $barang) }}" 
                                                   class="btn btn-warning" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @can('isAdmin')
                                                    <form action="{{ route('inventory.barang.destroy', $barang) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus barang {{ $barang->nama_barang }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $barangs->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Belum ada data barang</p>
                        <a href="{{ route('inventory.barang.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah Barang Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
