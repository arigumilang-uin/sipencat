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
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] overflow-hidden ring-1 ring-slate-100/50">
                @if($suppliers->count() > 0)
                    <table class="min-w-full">
                        <thead class="bg-indigo-50/40 border-b border-indigo-100/50">
                            <tr>
                                <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-bold uppercase tracking-wider text-indigo-400 w-16">No</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-indigo-400">Nama Supplier</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-indigo-400">Telepon</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-indigo-400">Alamat</th>
                                <th scope="col" class="relative py-4 pl-3 pr-6 text-center text-[11px] font-bold uppercase tracking-wider text-indigo-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($suppliers as $supplier)
                                <tr class="hover:bg-indigo-50/20 transition-colors duration-200 group">
                                    <td class="whitespace-nowrap py-5 pl-6 pr-3 text-sm text-slate-400 font-medium">{{ $suppliers->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-cyan-100 to-cyan-50 flex items-center justify-center text-cyan-600 font-bold border border-cyan-200 mr-4 text-xs shadow-sm">
                                                <i class="bi bi-shop text-sm"></i>
                                            </div>
                                            <span class="font-bold text-slate-700 group-hover:text-cyan-600 transition-colors">{{ $supplier->nama_supplier }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <a href="tel:{{ $supplier->telp }}" class="inline-flex items-center text-slate-500 hover:text-cyan-600 transition-colors font-mono text-xs bg-slate-50 px-2.5 py-1 rounded-md border border-slate-100 group-hover:bg-white group-hover:border-cyan-100">
                                            <i class="bi bi-telephone-fill mr-2 text-[10px] opacity-70"></i>
                                            {{ $supplier->telp }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500 max-w-xs truncate" title="{{ $supplier->alamat }}">
                                        {{ Str::limit($supplier->alamat, 50) }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-center text-sm font-medium">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('inventory.supplier.show', $supplier) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-200 shadow-sm" title="Detail">
                                                <i class="bi bi-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('inventory.supplier.edit', $supplier) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 shadow-sm" title="Edit">
                                                <i class="bi bi-pencil text-sm"></i>
                                            </a>
                                            
                                            @can('isAdmin')
                                                <form action="{{ route('inventory.supplier.destroy', $supplier) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->nama_supplier }}?')">
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
                        {{ $suppliers->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <i class="bi bi-shop text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-slate-800">Belum ada supplier</h3>
                        <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">Tambahkan supplier baru untuk memulai transaksi barang masuk.</p>
                        <div class="mt-8">
                            <a href="{{ route('inventory.supplier.create') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg hover:bg-indigo-500 hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-1">
                                <i class="bi bi-plus-circle mr-2 text-lg"></i>
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
