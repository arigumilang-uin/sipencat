@extends('layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')
@section('page-subtitle', 'Riwayat aktivitas sistem')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-funnel me-2"></i>
                    Filter
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.audit-logs.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tabel</label>
                            <select name="table_name" class="form-select form-select-sm">
                                <option value="">Semua Tabel</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table }}" {{ request('table_name') === $table ? 'selected' : '' }}>
                                        {{ $table }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Aksi</label>
                            <select name="action" class="form-select form-select-sm">
                                <option value="">Semua Aksi</option>
                                <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                                <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                                <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-select form-select-sm" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-select form-select-sm" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Riwayat Aktivitas
                    </h5>
                    <span class="badge bg-secondary">{{ $auditLogs->total() }} Record</span>
                </div>
            </div>
            <div class="card-body">
                @if($auditLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Waktu</th>
                                    <th width="15%">User</th>
                                    <th width="10%">Aksi</th>
                                    <th width="15%">Tabel</th>
                                    <th width="15%">IP Address</th>
                                    <th width="15%">User Agent</th>
                                    <th width="10%" class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLogs as $log)
                                    <tr>
                                        <td>{{ $auditLogs->firstItem() + $loop->index }}</td>
                                        <td>
                                            <small>{{ $log->created_at->format('d/m/Y') }}</small><br>
                                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <strong>{{ $log->user->name }}</strong><br>
                                                <small class="text-muted">{{ $log->user->role->label() }}</small>
                                            @else
                                                <span class="text-muted">System</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $actionBadge = match($log->action) {
                                                    'created' => 'success',
                                                    'updated' => 'warning',
                                                    'deleted' => 'danger',
                                                    default => 'secondary'
                                                };
                                                $actionIcon = match($log->action) {
                                                    'created' => 'plus-circle',
                                                    'updated' => 'pencil',
                                                    'deleted' => 'trash',
                                                    default => 'dot'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $actionBadge }}">
                                                <i class="bi bi-{{ $actionIcon }} me-1"></i>
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td>
                                            <code>{{ $log->table_name }}</code>
                                        </td>
                                        <td>
                                            <small class="font-monospace">{{ $log->ip_address }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted" title="{{ $log->user_agent }}">
                                                {{ Str::limit($log->user_agent, 30) }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.audit-logs.show', $log) }}" 
                                               class="btn btn-sm btn-info"
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $auditLogs->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Tidak ada audit log ditemukan</p>
                        @if(request()->hasAny(['table_name', 'action', 'start_date', 'end_date']))
                            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-secondary">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-3 border-info">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-info-circle text-info me-2"></i>
                    Tentang Audit Logs
                </h6>
                <p class="mb-0 small">
                    Audit logs mencatat semua aktivitas perubahan data di sistem termasuk <strong>CREATE</strong>, <strong>UPDATE</strong>, dan <strong>DELETE</strong>. 
                    Setiap log menyimpan informasi user yang melakukan aksi, waktu, IP address, dan perubahan data (old values vs new values).
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
