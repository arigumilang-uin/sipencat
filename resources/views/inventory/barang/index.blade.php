@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')
@section('page-subtitle', 'Manajemen master data barang')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Daftar Barang</h1>
        <p class="mt-2 text-sm text-gray-700">Daftar lengkap semua barang termasuk kode, nama, stok, dan harga terkini.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('inventory.barang.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Barang
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
                @if($barangs->count() > 0)
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-16">No</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kode</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Barang</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Stok</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Harga</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Min Stok</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($barangs as $barang)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">{{ $barangs->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono text-xs">{{ $barang->kode_barang }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-medium {{ $barang->stok > 0 ? 'text-gray-900' : 'text-red-600' }}">
                                        {{ number_format($barang->stok) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">{{ number_format($barang->min_stok) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                        @if($barang->isBelowMinStock())
                                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                Low
                                            </span>
                                        @elseif($barang->stok == 0)
                                            <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                Habis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                OK
                                            </span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('inventory.barang.show', $barang) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-50 transition-colors" title="Detail">
                                                <i class="bi bi-eye"></i><span class="sr-only">Detail</span>
                                            </a>
                                            <a href="{{ route('inventory.barang.edit', $barang) }}" class="text-amber-600 hover:text-amber-900 p-1 rounded-md hover:bg-amber-50 transition-colors" title="Edit">
                                                <i class="bi bi-pencil"></i><span class="sr-only">Edit</span>
                                            </a>
                                            @can('isAdmin')
                                                <form action="{{ route('inventory.barang.destroy', $barang) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus barang {{ $barang->nama_barang }}?')">
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
                        {{ $barangs->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-inbox text-4xl text-gray-300 block mb-3"></i>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada barang</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan barang baru ke inventaris.</p>
                        <div class="mt-6">
                            <a href="{{ route('inventory.barang.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Tambah Barang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
