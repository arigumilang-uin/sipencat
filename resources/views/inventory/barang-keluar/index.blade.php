@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')
@section('page-subtitle', 'Daftar transaksi pengeluaran barang')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Barang Keluar</h1>
        <p class="mt-2 text-sm text-gray-700">Catatan riwayat pengeluaran barang dari gudang ke tujuan.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('inventory.barang-keluar.create') }}" class="block rounded-md bg-rose-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600 transition-colors">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Barang Keluar
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between pointer-events-none sm:pointer-events-auto" onclick="document.getElementById('filter-panel').classList.toggle('hidden')">
        <h3 class="text-sm font-medium leading-6 text-gray-900 flex items-center">
            <i class="bi bi-funnel mr-2 text-indigo-500"></i> Filter Data
        </h3>
        <i class="bi bi-chevron-down text-gray-500 text-xs"></i>
    </div>
    <div id="filter-panel" class="p-4">
        <form action="{{ route('inventory.barang-keluar.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="barang_id" class="block text-xs font-medium text-gray-700">Barang</label>
                    <div class="mt-1">
                        <select id="barang_id" name="barang_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Semua Barang</option>
                            @foreach($barangs as $brg)
                                <option value="{{ $brg->id }}" {{ request('barang_id') == $brg->id ? 'selected' : '' }}>
                                    {{ $brg->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="start_date" class="block text-xs font-medium text-gray-700">Dari Tanggal</label>
                    <div class="mt-1">
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="end_date" class="block text-xs font-medium text-gray-700">Sampai Tanggal</label>
                    <div class="mt-1">
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="bi bi-search mr-2"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
                @if($barangKeluars->count() > 0)
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-16">No</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Barang</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Jumlah</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tujuan</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Dicatat Oleh</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($barangKeluars as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">{{ $barangKeluars->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <i class="bi bi-calendar3 mr-2 text-gray-400"></i>
                                            {{ $item->tanggal->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                        <div class="font-medium text-gray-900">{{ $item->barang->nama_barang }}</div>
                                        <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $item->barang->kode_barang }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-right">
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                            -{{ number_format($item->jumlah) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $item->tujuan }}">
                                        {{ Str::limit($item->tujuan, 30) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="font-medium text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $item->user->role->label() }}</div>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('inventory.barang-keluar.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-50 transition-colors" title="Detail">
                                                <i class="bi bi-eye"></i><span class="sr-only">Detail</span>
                                            </a>
                                            <a href="{{ route('inventory.barang-keluar.edit', $item) }}" class="text-amber-600 hover:text-amber-900 p-1 rounded-md hover:bg-amber-50 transition-colors" title="Edit">
                                                <i class="bi bi-pencil"></i><span class="sr-only">Edit</span>
                                            </a>
                                            @can('isAdmin')
                                                <form action="{{ route('inventory.barang-keluar.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus? Stok akan dikembalikan!')">
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
                        {{ $barangKeluars->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-box-arrow-up text-4xl text-gray-300 block mb-3"></i>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada transaksi keluar</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai catat pengeluaran barang dari gudang.</p>
                        <div class="mt-6">
                            <a href="{{ route('inventory.barang-keluar.create') }}" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Tambah Barang Keluar
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
