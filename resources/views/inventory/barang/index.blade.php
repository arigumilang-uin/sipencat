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
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] overflow-hidden ring-1 ring-slate-100/50">
                @if($barangs->count() > 0)
                    <table class="min-w-full">
                        <thead class="bg-indigo-50/40 border-b border-indigo-100/50">
                            <tr>
                                <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-bold uppercase tracking-wider text-indigo-400 w-16">No</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-indigo-400">Kode</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-indigo-400">Nama Barang</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold uppercase tracking-wider text-indigo-400">Stok</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold uppercase tracking-wider text-indigo-400">Harga</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold uppercase tracking-wider text-indigo-400">Min Stok</th>
                                <th scope="col" class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-indigo-400">Status</th>
                                <th scope="col" class="relative py-4 pl-3 pr-6 text-center text-[11px] font-bold uppercase tracking-wider text-indigo-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($barangs as $barang)
                                <tr class="hover:bg-indigo-50/20 transition-colors duration-200 group">
                                    <td class="whitespace-nowrap py-5 pl-6 pr-3 text-sm text-slate-400 font-medium">{{ $barangs->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-mono font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $barang->kode_barang }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm font-bold text-slate-700 group-hover:text-indigo-700 transition-colors">{{ $barang->nama_barang }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-right font-bold {{ $barang->stok > 0 ? 'text-slate-700' : 'text-rose-500' }}">
                                        {{ number_format($barang->stok) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-right text-slate-500 font-mono">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-right text-slate-400">{{ number_format($barang->min_stok) }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-center">
                                        @if($barang->isBelowMinStock())
                                            <span class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-600 ring-1 ring-inset ring-amber-500/10 shadow-sm">
                                                Low Stock
                                            </span>
                                        @elseif($barang->stok == 0)
                                            <span class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/10 shadow-sm">
                                                Out of Stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600 ring-1 ring-inset ring-emerald-500/10 shadow-sm">
                                                Available
                                            </span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-center text-sm font-medium">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('inventory.barang.show', $barang) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-200 shadow-sm" title="Detail">
                                                <i class="bi bi-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('inventory.barang.edit', $barang) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 shadow-sm" title="Edit">
                                                <i class="bi bi-pencil text-sm"></i>
                                            </a>
                                            @can('isAdmin')
                                                <form action="{{ route('inventory.barang.destroy', $barang) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus barang {{ $barang->nama_barang }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm" title="Hapus">
                                                        <i class="bi bi-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="bg-white px-6 py-4 border-t border-slate-100">
                        {{ $barangs->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <i class="bi bi-box-seam text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-slate-800">Belum ada barang</h3>
                        <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">Inventaris kosong. Mulai dengan menambahkan barang baru.</p>
                        <div class="mt-8">
                            <a href="{{ route('inventory.barang.create') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg hover:bg-indigo-500 hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-1">
                                <i class="bi bi-plus-circle mr-2 text-lg"></i>
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
