@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Overview & Analytics')
@section('page-subtitle', 'Monitoring performa sistem dan aktivitas terkini.')

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
    
    <!-- Card 1: Users -->
    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-enter delay-100 group">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-indigo-50 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Total Pengguna</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-slate-800 tracking-tight">{{ $stats['total_users'] }}</span>
                    <span class="text-sm font-medium text-emerald-500">+12%</span>
                </div>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 shadow-sm">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-indigo-500 w-[70%] rounded-full"></div>
        </div>
    </div>

    <!-- Card 2: Items -->
    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-enter delay-200 group">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-emerald-50 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Total Barang</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-slate-800 tracking-tight">{{ $stats['total_barang'] }}</span>
                    <span class="text-sm font-medium text-slate-400">Item</span>
                </div>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 shadow-sm">
                <i class="bi bi-box-seam-fill text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 w-[55%] rounded-full"></div>
        </div>
    </div>

    <!-- Card 3: Low Stock -->
    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-enter delay-300 group">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-amber-50 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Stok Kritis</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-slate-800 tracking-tight">{{ $stats['low_stock_items'] }}</span>
                    <span class="text-sm font-medium text-amber-500">Perlu Tindakan</span>
                </div>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600 shadow-sm animate-pulse">
                <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-amber-500 w-[{{ min($stats['low_stock_items'] * 5, 100) }}%] rounded-full"></div>
        </div>
    </div>

    <!-- Card 4: Suppliers -->
    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-enter delay-300 group">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-cyan-50 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Total Supplier</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-slate-800 tracking-tight">{{ $stats['total_suppliers'] }}</span>
                    <span class="text-sm font-medium text-slate-400">Rekanan</span>
                </div>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600 shadow-sm">
                <i class="bi bi-shop-window text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-cyan-500 w-[40%] rounded-full"></div>
        </div>
    </div>
</div>

<!-- Layout Two Column: Recent Audit + Quick Actions (Future) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 animate-enter delay-200">
    
    <!-- Audit Log -->
    <div class="lg:col-span-2 rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-5 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Aktivitas Terkini</h3>
                <p class="text-xs text-slate-500 mt-1">Pantau perubahan data secara real-time.</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="group inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                Lihat Semua 
                <i class="bi bi-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Waktu</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">User</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Target</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($stats['recent_audit_logs'] as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                <span class="font-mono text-xs">{{ $log->created_at->format('H:i') }}</span>
                                <span class="text-xs text-slate-400 ml-1">{{ $log->created_at->format('d/m') }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">
                                <div class="flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold mr-2 text-slate-500 uppercase border border-slate-200">
                                        {{ substr($log->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    {{ $log->user->name ?? 'System' }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                    {{ $log->action === 'created' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 
                                      ($log->action === 'updated' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 
                                      ($log->action === 'deleted' ? 'bg-rose-50 text-rose-700 ring-rose-600/20' : 'bg-slate-50 text-slate-700 ring-slate-600/20')) }}">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                <code class="bg-indigo-50 px-1.5 py-0.5 rounded text-xs text-indigo-600 font-mono border border-indigo-100">{{ $log->table_name }}</code>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-400 font-mono text-xs">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400 italic">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-journal-x text-2xl mb-2 text-slate-300"></i>
                                    Belum ada log aktivitas.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Access / Info Panel -->
    <div class="space-y-6">
        <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 shadow-lg p-6 text-white relative overflow-hidden">
             <div class="absolute top-0 right-0 -mr-8 -mt-8 h-32 w-32 rounded-full bg-white opacity-10 blur-xl"></div>
             <h3 class="text-lg font-bold mb-2 relative z-10">Status Sistem</h3>
             <div class="flex items-center gap-3 relative z-10">
                 <div class="h-3 w-3 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.7)]"></div>
                 <span class="font-medium text-indigo-100">Berjalan Normal</span>
             </div>
             <p class="mt-4 text-sm text-indigo-100/80 leading-relaxed relative z-10">
                 Sistem berjalan optimal. Tidak ada kendala teknis yang terdeteksi pada server dan database.
             </p>
             <div class="mt-6 pt-6 border-t border-white/10 relative z-10">
                 <div class="flex justify-between text-xs text-indigo-200">
                     <span>Beban Server</span>
                     <span>24%</span>
                 </div>
                 <div class="w-full bg-black/20 rounded-full h-1.5 mt-2">
                     <div class="bg-emerald-400 h-1.5 rounded-full" style="width: 24%"></div>
                 </div>
             </div>
        </div>
        
        <div class="rounded-2xl bg-white shadow-sm border border-slate-100 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Tautan Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.create') }}" class="group flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-100 transition-all duration-200">
                    <span class="text-sm font-medium text-slate-600 group-hover:text-indigo-700">Tambah User Baru</span>
                    <i class="bi bi-plus-lg text-indigo-500"></i>
                </a>
                <a href="{{ route('inventory.barang.create') }}" class="group flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 transition-all duration-200">
                    <span class="text-sm font-medium text-slate-600 group-hover:text-emerald-700">Tambah Barang Baru</span>
                    <i class="bi bi-box-seam text-emerald-500"></i>
                </a>
                <a href="{{ route('reports.index') }}" class="group flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-cyan-50 border border-slate-100 hover:border-cyan-100 transition-all duration-200">
                    <span class="text-sm font-medium text-slate-600 group-hover:text-cyan-700">Lihat Laporan Lengkap</span>
                    <i class="bi bi-bar-chart text-cyan-500"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
