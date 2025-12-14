@extends('layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')
@section('page-subtitle', 'Riwayat aktivitas sistem')

@section('content')
<div class="space-y-8">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Audit Logs</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau seluruh aktivitas dan perubahan data dalam sistem secara real-time.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-12 items-end">
                <div class="sm:col-span-1 lg:col-span-3">
                    <label for="table_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Tabel</label>
                    <div class="relative">
                        <select name="table_name" id="table_name" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-4 pr-8 text-sm placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                            <option value="">Semua Tabel</option>
                            @foreach($tables as $table)
                                <option value="{{ $table }}" {{ request('table_name') === $table ? 'selected' : '' }}>
                                    {{ $table }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <i class="bi bi-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-1 lg:col-span-2">
                    <label for="action" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Aksi</label>
                    <div class="relative">
                        <select name="action" id="action" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-4 pr-8 text-sm placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                            <option value="">Semua Aksi</option>
                            <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <i class="bi bi-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-1 lg:col-span-3">
                    <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                </div>

                <div class="sm:col-span-1 lg:col-span-3">
                    <label for="end_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                </div>

                <div class="sm:col-span-2 lg:col-span-1">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="bi bi-search text-lg"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Stats & Table -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] overflow-hidden ring-1 ring-slate-100/50">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-clock-history text-indigo-500"></i>
                Riwayat Aktivitas
            </h3>
            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                {{ $auditLogs->total() }} Record
            </span>
        </div>

        @if($auditLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-indigo-50/20 text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">User</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-center">Aksi</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Tabel</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Detail</th>
                            <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($auditLogs as $log)
                            <tr class="group hover:bg-indigo-50/10 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">{{ $log->created_at->format('d/m/Y') }}</span>
                                        <span class="text-xs text-slate-400 font-mono">{{ $log->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->user)
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs ring-2 ring-white shadow-sm">
                                                {{ substr($log->user->name, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-700">{{ $log->user->name }}</span>
                                                <span class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $log->user->role->label() }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                            System
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $badgeClass = match($log->action) {
                                            'created' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'updated' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'deleted' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                            default => 'bg-slate-50 text-slate-700 ring-slate-600/20'
                                        };
                                        $icon = match($log->action) {
                                            'created' => 'bi-plus-lg',
                                            'updated' => 'bi-pencil-fill',
                                            'deleted' => 'bi-trash-fill',
                                            default => 'bi-dot'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $badgeClass }} uppercase tracking-wider">
                                        <i class="bi {{ $icon }} mr-1.5 text-[10px]"></i>
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="rounded bg-slate-100 px-[0.3rem] py-[0.1rem] font-mono text-xs font-bold text-indigo-600">
                                        {{ $log->table_name }}
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-xs text-slate-500" title="IP Address">
                                            <i class="bi bi-globe text-slate-400"></i>
                                            <span class="font-mono">{{ $log->ip_address }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.audit-logs.show', $log) }}" 
                                       class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 shadow-sm ring-1 ring-slate-900/5">
                                        <i class="bi bi-chevron-right text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                {{ $auditLogs->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="rounded-full bg-slate-50 p-4 mb-4 ring-1 ring-slate-100">
                    <i class="bi bi-inbox text-4xl text-slate-300"></i>
                </div>
                <h3 class="mt-2 text-sm font-bold text-slate-900">Tidak ada log ditemukan</h3>
                <p class="mt-1 text-sm text-slate-500 mb-6 max-w-sm">Coba sesuaikan filter pencarian Anda atau cek kembali nanti.</p>
                @if(request()->hasAny(['table_name', 'action', 'start_date', 'end_date']))
                    <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all duration-200">
                        <i class="bi bi-x-lg mr-2 text-xs"></i>
                        Reset Filter
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Info Card -->
    <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5 flex gap-4 items-start">
        <div class="flex-shrink-0">
            <i class="bi bi-info-circle-fill text-xl text-indigo-500"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-indigo-900 mb-1">Tentang Audit Logs</h4>
            <p class="text-sm text-indigo-700 leading-relaxed">
                Audit logs sistem mencatat semua aktivitas perubahan data secara otomatis. 
                Sistem merekam operasi <strong>CREATE</strong>, <strong>UPDATE</strong>, dan <strong>DELETE</strong> beserata metadata pengguna, waktu, dan alamat IP untuk keperluan tracebility dan keamanan.
            </p>
        </div>
    </div>
</div>
@endsection
