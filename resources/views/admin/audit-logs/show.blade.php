@extends('layouts.app')

@section('title', 'Detail Audit Log')
@section('page-title', 'Detail Audit Log')
@section('page-subtitle', 'Informasi lengkap aktivitas sistem')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Log #{{ $auditLog->id }}</h1>
        <a href="{{ route('admin.audit-logs.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 flex items-center">
                    <i class="bi bi-file-text mr-2 text-indigo-500"></i>
                    Informasi Audit Log
                </h3>
                
                @php
                    $actionBadge = match($auditLog->action) {
                        'created' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                        'updated' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        'deleted' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                        default => 'bg-slate-50 text-slate-700 ring-slate-600/20'
                    };
                    $actionIcon = match($auditLog->action) {
                        'created' => 'bi-plus-circle',
                        'updated' => 'bi-pencil',
                        'deleted' => 'bi-trash',
                        default => 'bi-circle'
                    };
                @endphp
                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $actionBadge }}">
                    <i class="bi {{ $actionIcon }} mr-1.5"></i>
                    {{ strtoupper($auditLog->action) }}
                </span>
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column: Basic Info -->
            <div class="space-y-6">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Waktu & User</h4>
                    <dl class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <i class="bi bi-calendar3"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <dt class="text-xs text-slate-500 font-medium">Waktu Kejadian</dt>
                                <dd class="text-sm font-bold text-slate-900">{{ $auditLog->created_at->format('d F Y') }}</dd>
                                <dd class="text-xs text-slate-400">{{ $auditLog->created_at->format('H:i:s') }} WIB</dd>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <dt class="text-xs text-slate-500 font-medium">User</dt>
                                @if($auditLog->user)
                                    <dd class="text-sm font-bold text-slate-900">{{ $auditLog->user->name }}</dd>
                                    <dd class="text-xs text-slate-400 font-mono">{{ $auditLog->user->username }} ({{ $auditLog->user->role->label() }})</dd>
                                @else
                                    <dd class="text-sm font-bold text-slate-900 italic">System / Deleted User</dd>
                                @endif
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Right Column: Technical Info -->
            <div class="space-y-6">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Detail Teknis</h4>
                    <dl class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-600">
                                    <i class="bi bi-table"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <dt class="text-xs text-slate-500 font-medium">Tabel Target</dt>
                                <dd class="text-sm font-mono font-bold text-slate-900">{{ $auditLog->table_name }}</dd>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-600">
                                    <i class="bi bi-globe"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <dt class="text-xs text-slate-500 font-medium">IP Address</dt>
                                <dd class="text-sm font-mono font-bold text-slate-900">{{ $auditLog->ip_address }}</dd>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-600">
                                    <i class="bi bi-browser-chrome"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <dt class="text-xs text-slate-500 font-medium">User Agent</dt>
                                <dd class="text-xs text-slate-600 break-all">{{ $auditLog->user_agent }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Changes Detail -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center">
                <i class="bi bi-arrow-left-right mr-2 text-indigo-500"></i>
                Detail Perubahan Data
            </h3>
        </div>
        
        <div class="p-6">
            @if($auditLog->action === 'created' && $auditLog->new_values)
                <!-- Created Context -->
                <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4 mb-6">
                    <div class="flex items-center">
                        <i class="bi bi-check-circle-fill text-emerald-500 mr-2"></i>
                        <span class="text-sm font-bold text-emerald-900">Data Baru Dibuat</span>
                    </div>
                </div>
                
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-emerald-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider w-1/3">Field</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider w-2/3">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($auditLog->new_values as $field => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">{{ $field }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600 break-all">
                                        @if(is_array($value))
                                            <pre class="bg-slate-50 p-2 rounded text-xs font-mono">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @elseif($auditLog->action === 'deleted' && $auditLog->old_values)
                <!-- Deleted Context -->
                <div class="rounded-xl bg-rose-50 border border-rose-100 p-4 mb-6">
                    <div class="flex items-center">
                        <i class="bi bi-trash-fill text-rose-500 mr-2"></i>
                        <span class="text-sm font-bold text-rose-900">Data Dihapus</span>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-rose-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-rose-800 uppercase tracking-wider w-1/3">Field</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-rose-800 uppercase tracking-wider w-2/3">Nilai Sebelum Dihapus</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($auditLog->old_values as $field => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">{{ $field }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600 break-all">
                                        @if(is_array($value))
                                            <pre class="bg-slate-50 p-2 rounded text-xs font-mono">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @elseif($auditLog->action === 'updated' && $auditLog->old_values && $auditLog->new_values)
                <!-- Updated Context -->
                <div class="rounded-xl bg-amber-50 border border-amber-100 p-4 mb-6">
                    <div class="flex items-center">
                        <i class="bi bi-pencil-fill text-amber-500 mr-2"></i>
                        <span class="text-sm font-bold text-amber-900">Data Diperbarui</span>
                        <span class="text-xs text-amber-700 ml-2 border-l border-amber-200 pl-2">Menampilkan perbandingan nilai sebelum dan sesudah perubahan</span>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-amber-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-amber-900/70 uppercase tracking-wider w-1/4">Field</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-amber-900/70 uppercase tracking-wider w-1/3">
                                    <i class="bi bi-arrow-left mr-1"></i> Nilai Lama
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-amber-900/70 uppercase tracking-wider w-1/3">
                                    <i class="bi bi-arrow-right mr-1"></i> Nilai Baru
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @php
                                $allFields = array_unique(array_merge(
                                    array_keys($auditLog->old_values ?? []),
                                    array_keys($auditLog->new_values ?? [])
                                ));
                            @endphp
                            
                            @foreach($allFields as $field)
                                @php
                                    $oldValue = $auditLog->old_values[$field] ?? null;
                                    $newValue = $auditLog->new_values[$field] ?? null;
                                    
                                    // Serialize arrays for comparison and display
                                    $oldDisplay = is_array($oldValue) ? json_encode($oldValue) : $oldValue;
                                    $newDisplay = is_array($newValue) ? json_encode($newValue) : $newValue;
                                    
                                    $hasChanged = $oldDisplay !== $newDisplay;
                                @endphp
                                
                                <tr class="{{ $hasChanged ? 'bg-amber-50/30' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                                        {{ $field }}
                                        @if($hasChanged)
                                            <i class="bi bi-exclamation-circle-fill text-amber-400 ml-1" title="Berubah"></i>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 break-all font-mono">
                                        @if(is_array($oldValue))
                                            <pre class="bg-slate-100 p-1.5 rounded text-xs">{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</pre>
                                        @elseif(is_null($oldValue))
                                            <span class="text-slate-400 italic">NULL</span>
                                        @else
                                            {{ $oldValue }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-900 break-all font-mono {{ $hasChanged ? 'bg-amber-100/20 ring-1 ring-inset ring-amber-100' : '' }}">
                                        @if(is_array($newValue))
                                            <pre class="bg-indigo-50 p-1.5 rounded text-xs text-indigo-900">{{ json_encode($newValue, JSON_PRETTY_PRINT) }}</pre>
                                        @elseif(is_null($newValue))
                                            <span class="text-slate-400 italic">NULL</span>
                                        @else
                                            {{ $newValue }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-8 text-center">
                    <div class="inline-flex rounded-full bg-slate-100 p-3 mb-3">
                        <i class="bi bi-info-lg text-slate-400 text-xl"></i>
                    </div>
                    <h3 class="text-sm font-medium text-slate-900">Tidak ada detail perubahan data tersimpan</h3>
                    <p class="mt-1 text-sm text-slate-500">Aksi ini mungkin tidak mengubah data atau log detail dinonaktifkan.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Raw Data (Developer Mode) - Toggleable -->
    <div x-data="{ showRaw: false }" class="mt-8">
        <button @click="showRaw = !showRaw" class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-slate-600 transition-colors mb-4">
            <i class="bi" :class="showRaw ? 'bi-chevron-down' : 'bi-chevron-right'"></i>
            <span class="ml-2">Raw JSON Data (Developer Info)</span>
        </button>
        
        <div x-show="showRaw" x-collapse style="display: none;">
            <div class="rounded-3xl bg-slate-900 shadow-xl overflow-hidden ring-1 ring-white/10">
                <div class="border-b border-slate-800 px-6 py-4 bg-slate-800/50">
                    <h3 class="text-sm font-mono font-bold text-slate-300">JSON Data Payload</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 font-mono text-xs">
                    @if($auditLog->old_values)
                        <div>
                            <h4 class="text-rose-400 mb-2">Old Values</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl text-slate-300 overflow-x-auto border border-slate-800">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif
                    @if($auditLog->new_values)
                        <div>
                            <h4 class="text-emerald-400 mb-2">New Values</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl text-slate-300 overflow-x-auto border border-slate-800">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
