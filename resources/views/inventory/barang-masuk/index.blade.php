@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')
@section('page-subtitle', 'Daftar transaksi barang masuk')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Barang Masuk</h1>
        <p class="mt-2 text-sm text-gray-700">Catatan riwayat penerimaan barang dari supplier ke gudang.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('inventory.barang-masuk.create') }}" class="block rounded-md bg-emerald-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Barang Masuk
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
        <form action="{{ route('inventory.barang-masuk.index') }}" method="GET">
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
                    <label for="supplier_id" class="block text-xs font-medium text-gray-700">Supplier</label>
                    <div class="mt-1">
                        <select id="supplier_id" name="supplier_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Semua Supplier</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-1">
                    <label for="start_date" class="block text-xs font-medium text-gray-700">Dari Tanggal</label>
                    <div class="mt-1">
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="sm:col-span-1">
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
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] overflow-hidden ring-1 ring-slate-100/50">
                @if($barangMasuks->count() > 0)
                    <table class="min-w-full">
                        <thead class="bg-emerald-50/40 border-b border-emerald-100/50">
                            <tr>
                                <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-bold uppercase tracking-wider text-emerald-600 w-16">No</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-emerald-600">Tanggal</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-emerald-600">Barang</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-emerald-600">Supplier</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold uppercase tracking-wider text-emerald-600">Jumlah</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-emerald-600">Dicatat Oleh</th>
                                <th scope="col" class="hidden px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-emerald-600 lg:table-cell">Keterangan</th>
                                <th scope="col" class="relative py-4 pl-3 pr-6 text-center text-[11px] font-bold uppercase tracking-wider text-emerald-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($barangMasuks as $item)
                                <tr class="hover:bg-emerald-50/20 transition-colors duration-200 group">
                                    <td class="whitespace-nowrap py-5 pl-6 pr-3 text-sm text-slate-400 font-medium">{{ $barangMasuks->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <div class="flex items-center text-slate-600">
                                            <i class="bi bi-calendar3 mr-2 text-slate-400 text-xs"></i>
                                            <span class="font-medium">{{ $item->tanggal->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <div class="font-bold text-slate-700 group-hover:text-emerald-700 transition-colors">{{ $item->barang->nama_barang }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5 uppercase tracking-wide bg-slate-50 inline-block px-1.5 rounded">{{ $item->barang->kode_barang }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <div class="flex items-center">
                                            <div class="h-6 w-6 rounded-full bg-cyan-50 text-cyan-500 flex items-center justify-center mr-2">
                                                <i class="bi bi-shop text-[10px]"></i>
                                            </div>
                                            <span class="text-slate-600">{{ $item->supplier->nama_supplier }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-right">
                                        <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-sm font-bold text-emerald-600 shadow-sm border border-emerald-100">
                                            +{{ number_format($item->jumlah) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <div class="font-medium text-slate-700">{{ $item->user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $item->user->role->label() }}</div>
                                    </td>
                                    <td class="hidden px-6 py-5 text-sm text-slate-500 lg:table-cell max-w-xs truncate italic">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-center text-sm font-medium">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('inventory.barang-masuk.show', $item) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-200 shadow-sm" title="Detail">
                                                <i class="bi bi-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('inventory.barang-masuk.edit', $item) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 shadow-sm" title="Edit">
                                                <i class="bi bi-pencil text-sm"></i>
                                            </a>
                                            @can('isAdmin')
                                                <form action="{{ route('inventory.barang-masuk.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus? Stok akan dikurangi!')">
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
                        {{ $barangMasuks->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="h-20 w-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <i class="bi bi-box-arrow-in-down text-4xl text-emerald-300"></i>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-slate-800">Belum ada transaksi masuk</h3>
                        <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">Catat penerimaan barang pertama Anda sekarang.</p>
                        <div class="mt-8">
                            <a href="{{ route('inventory.barang-masuk.create') }}" class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg hover:bg-emerald-500 hover:shadow-emerald-500/30 transition-all duration-300 transform hover:-translate-y-1">
                                <i class="bi bi-plus-circle mr-2 text-lg"></i>
                                Catat Barang Masuk
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
