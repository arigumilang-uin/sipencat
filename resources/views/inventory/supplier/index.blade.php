@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')
@section('page-subtitle', 'Manajemen data pemasok barang')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Daftar Supplier</h1>
        <p class="mt-2 text-sm text-gray-700">Daftar rekanan supplier tempat pengadaan barang inventory.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('inventory.supplier.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Supplier
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
                @if($suppliers->count() > 0)
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-16">No</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Supplier</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Telepon</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Alamat</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">{{ $suppliers->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-md bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold border border-cyan-200 mr-3 text-xs">
                                                <i class="bi bi-shop"></i>
                                            </div>
                                            {{ $supplier->nama_supplier }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">
                                        <a href="tel:{{ $supplier->telp }}" class="hover:text-indigo-600 transition-colors">
                                            <i class="bi bi-telephone mr-1 text-xs"></i>
                                            {{ $supplier->telp }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $supplier->alamat }}">
                                        {{ Str::limit($supplier->alamat, 50) }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('inventory.supplier.show', $supplier) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-50 transition-colors" title="Detail">
                                                <i class="bi bi-eye"></i><span class="sr-only">Detail</span>
                                            </a>
                                            <a href="{{ route('inventory.supplier.edit', $supplier) }}" class="text-amber-600 hover:text-amber-900 p-1 rounded-md hover:bg-amber-50 transition-colors" title="Edit">
                                                <i class="bi bi-pencil"></i><span class="sr-only">Edit</span>
                                            </a>
                                            
                                            @can('isAdmin')
                                                <form action="{{ route('inventory.supplier.destroy', $supplier) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->nama_supplier }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors" title="Hapus">
                                                        <i class="bi bi-trash"></i><span class="sr-only">Hapus</span>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $suppliers->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-shop text-4xl text-gray-300 block mb-3"></i>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada supplier</h3>
                        <p class="mt-1 text-sm text-gray-500">Tambahkan supplier baru untuk memulai transaksi barang masuk.</p>
                        <div class="mt-6">
                            <a href="{{ route('inventory.supplier.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Tambah Supplier
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
