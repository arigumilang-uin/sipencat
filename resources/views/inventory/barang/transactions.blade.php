@extends('layouts.app')

@section('title', 'Riwayat Transaksi - ' . $barang->nama_barang)
@section('page-title', 'Riwayat Transaksi')
@section('page-subtitle', 'Detail lengkap semua transaksi masuk dan keluar')

@section('content')
<div class="max-w-6xl mx-auto space-y-8" x-data="{ 
    selectedIds: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selectedIds = [...document.querySelectorAll('input[name=\'transaction_ids[]\']')].map(cb => cb.value);
        } else {
            this.selectedIds = [];
        }
    },
    updateSelectAll() {
        const checkboxes = document.querySelectorAll('input[name=\'transaction_ids[]\']');
        this.selectAll = checkboxes.length > 0 && this.selectedIds.length === checkboxes.length;
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Riwayat Transaksi Barang</h1>
            <p class="text-sm text-slate-600 mt-1">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('inventory.barang.show', $barang) }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
                Kembali ke Detail
            </a>
            <a href="{{ route('inventory.barang.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="bi bi-list-ul mr-2"></i>
                Daftar Barang
            </a>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Stok Saat Ini</span>
                <span class="text-2xl font-bold text-slate-900">{{ number_format($barang->stok) }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Total Transaksi</span>
                <span class="text-2xl font-bold text-slate-900">{{ $transactions->count() }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Total Masuk</span>
                <span class="text-2xl font-bold text-emerald-600">+{{ number_format($transactions->where('type', 'masuk')->sum('jumlah')) }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Total Keluar</span>
                <span class="text-2xl font-bold text-rose-600">-{{ number_format($transactions->where('type', 'keluar')->sum('jumlah')) }}</span>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar (Only for Admin) -->
    @can('isAdmin')
        <div x-show="selectedIds.length > 0" 
             x-transition
             class="rounded-3xl bg-rose-600 text-white shadow-lg shadow-rose-200 p-4 flex items-center justify-between">
            <div class="flex items-center">
                <i class="bi bi-check2-square text-2xl mr-3"></i>
                <div>
                    <h3 class="font-bold"><span x-text="selectedIds.length"></span> Transaksi Dipilih</h3>
                    <p class="text-xs text-rose-100">Stok akan otomatis disesuaikan setelah penghapusan</p>
                </div>
            </div>
            <form action="{{ route('inventory.barang.transactions.bulk-delete', $barang) }}" 
                  method="POST" 
                  onsubmit="return confirm('⚠️ PERHATIAN!\n\nApakah Anda yakin ingin menghapus ' + selectedIds.length + ' transaksi?\n\n✓ Stok akan disesuaikan otomatis\n✓ Semua aktivitas tercatat di audit log\n✗ Tindakan ini TIDAK BISA dibatalkan\n\nLanjutkan?')"
                  class="flex items-center gap-3">
                @csrf
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="transaction_ids[]" x-bind:value="id">
                </template>
                <button type="button" 
                        @click="selectedIds = []; selectAll = false"
                        class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 transition-colors text-sm font-bold">
                    <i class="bi bi-x-circle mr-1"></i> Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2 rounded-lg bg-white text-rose-600 hover:bg-rose-50 transition-colors text-sm font-bold shadow-lg">
                    <i class="bi bi-trash mr-1"></i> Hapus Transaksi Terpilih
                </button>
            </form>
        </div>
    @endcan

    <!-- Transactions Table -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 flex items-center">
                <i class="bi bi-clock-history mr-2 text-indigo-500"></i>
                Riwayat Lengkap ({{ $transactions->count() }} Transaksi)
            </h3>
            
            @can('isAdmin')
                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-500" x-show="selectedIds.length > 0">
                        <span x-text="selectedIds.length"></span> dipilih
                    </span>
                </div>
            @endcan
        </div>

        @if($transactions->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex rounded-full bg-slate-100 p-4 mb-4">
                    <i class="bi bi-inbox text-slate-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Transaksi</h3>
                <p class="text-slate-600">Barang ini belum memiliki riwayat transaksi masuk atau keluar.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            @can('isAdmin')
                                <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">
                                    <input type="checkbox" 
                                           x-model="selectAll"
                                           @change="toggleAll()"
                                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                </th>
                            @endcan
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">No</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Jenis</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Jumlah</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Stok Sebelum</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Stok Sesudah</th>
                            <th class="px-6 py-4 font-bold text-slate-700">Partner/Tujuan</th>
                            <th class="px-6 py-4 font-bold text-slate-700">Keterangan</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">Dicatat Oleh</th>
                            <th class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $index => $transaction)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                @can('isAdmin')
                                    <td class="px-6 py-4">
                                        <input type="checkbox" 
                                               name="transaction_ids[]" 
                                               value="{{ $transaction['type'] }}-{{ $transaction['id'] }}"
                                               x-model="selectedIds"
                                               @change="updateSelectAll()"
                                               class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                    </td>
                                @endcan
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-slate-700 whitespace-nowrap">
                                    <div class="font-medium">{{ $transaction['tanggal']->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $transaction['created_at']->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction['type'] === 'masuk')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            <i class="bi bi-arrow-down-circle mr-1"></i> Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                            <i class="bi bi-arrow-up-circle mr-1"></i> Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction['type'] === 'masuk')
                                        <span class="text-emerald-600 font-bold">+{{ number_format($transaction['jumlah']) }}</span>
                                    @else
                                        <span class="text-rose-600 font-bold">-{{ number_format($transaction['jumlah']) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium whitespace-nowrap">{{ number_format($transaction['stock_before']) }}</td>
                                <td class="px-6 py-4 text-slate-900 font-bold whitespace-nowrap">{{ number_format($transaction['stock_after']) }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-700">{{ $transaction['partner'] }}</div>
                                    @if($transaction['partner_detail'] !== '-')
                                        <div class="text-xs text-slate-500">{{ $transaction['partner_detail'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate" title="{{ $transaction['keterangan'] }}">
                                    {{ $transaction['keterangan'] }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 whitespace-nowrap">{{ $transaction['user'] }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($transaction['type'] === 'masuk')
                                        <a href="{{ route('inventory.barang-masuk.show', $transaction['id']) }}" 
                                           class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
                                            <i class="bi bi-eye mr-1"></i> Detail
                                        </a>
                                    @else
                                        <a href="{{ route('inventory.barang-keluar.show', $transaction['id']) }}" 
                                           class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
                                            <i class="bi bi-eye mr-1"></i> Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    @can('isAdmin')
        <div class="rounded-2xl bg-amber-50 border border-amber-100 p-4">
            <div class="flex items-start">
                <i class="bi bi-info-circle-fill text-amber-500 text-xl mr-3 mt-0.5"></i>
                <div class="text-sm text-amber-800">
                    <h4 class="font-bold mb-1">Informasi Penghapusan Transaksi</h4>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Centang transaksi yang ingin dihapus, lalu klik tombol "Hapus Transaksi Terpilih"</li>
                        <li>Stok barang akan <strong>otomatis disesuaikan</strong> (dikurangi untuk transaksi masuk, ditambah untuk transaksi keluar)</li>
                        <li>Setiap penghapusan <strong>tercatat di audit log</strong> untuk integritas data</li>
                        <li>Setelah semua transaksi dihapus, Anda bisa menghapus barang dari daftar barang</li>
                    </ul>
                </div>
            </div>
        </div>
    @endcan
</div>
@endsection
