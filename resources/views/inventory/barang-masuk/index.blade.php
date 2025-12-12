@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')
@section('page-subtitle', 'Daftar transaksi barang masuk')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('inventory.barang-masuk.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Barang</label>
                            <select name="barang_id" class="form-select form-select-sm">
                                <option value="">Semua Barang</option>
                                @foreach($barangs as $brg)
                                    <option value="{{ $brg->id }}" {{ request('barang_id') == $brg->id ? 'selected' : '' }}>
                                        {{ $brg->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Supplier</label>
                            <select name="supplier_id" class="form-select form-select-sm">
                                <option value="">Semua Supplier</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-box-arrow-in-down me-2"></i>
                        Daftar Barang Masuk
                    </h5>
                    <a href="{{ route('inventory.barang-masuk.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Barang Masuk
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($barangMasuks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="20%">Barang</th>
                                    <th width="18%">Supplier</th>
                                    <th width="10%" class="text-end">Jumlah</th>
                                    <th width="15%">Dicatat Oleh</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangMasuks as $item)
                                    <tr>
                                        <td>{{ $barangMasuks->firstItem() + $loop->index }}</td>
                                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                                        <td>
                                            <strong>{{ $item->barang->nama_barang }}</strong><br>
                                            <small class="text-muted"><code>{{ $item->barang->kode_barang }}</code></small>
                                        </td>
                                        <td>{{ $item->supplier->nama_supplier }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-success">+{{ number_format($item->jumlah) }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $item->user->name }}</small><br>
                                            <small class="text-muted">{{ $item->user->role->label() }}</small>
                                        </td>
                                        <td><small>{{ Str::limit($item->keterangan ?? '-', 30) }}</small></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('inventory.barang-masuk.show', $item) }}" class="btn btn-info" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('inventory.barang-masuk.edit', $item) }}" class="btn btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @can('isAdmin')
                                                    <form action="{{ route('inventory.barang-masuk.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus? Stok akan dikurangi!')">
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
                        {{ $barangMasuks->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Belum ada transaksi barang masuk</p>
                        <a href="{{ route('inventory.barang-masuk.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah Transaksi Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
