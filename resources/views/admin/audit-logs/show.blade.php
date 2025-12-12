@extends('layouts.app')

@section('title', 'Detail Audit Log')
@section('page-title', 'Detail Audit Log')
@section('page-subtitle', 'Informasi lengkap aktivitas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Log Info Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2"></i>
                        Informasi Audit Log
                    </h5>
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="40%" class="text-muted">
                                        <i class="bi bi-calendar me-2"></i>Waktu
                                    </td>
                                    <td><strong>{{ $auditLog->created_at->format('d F Y, H:i:s') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-person me-2"></i>User
                                    </td>
                                    <td>
                                        @if($auditLog->user)
                                            <strong>{{ $auditLog->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $auditLog->user->username }} ({{ $auditLog->user->role->label() }})</small>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-activity me-2"></i>Aksi
                                    </td>
                                    <td>
                                        @php
                                            $actionBadge = match($auditLog->action) {
                                                'created' => 'success',
                                                'updated' => 'warning',
                                                'deleted' => 'danger',
                                                default => 'secondary'
                                            };
                                            $actionIcon = match($auditLog->action) {
                                                'created' => 'plus-circle',
                                                'updated' => 'pencil',
                                                'deleted' => 'trash',
                                                default => 'dot'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $actionBadge }} fs-6">
                                            <i class="bi bi-{{ $actionIcon }} me-1"></i>
                                            {{ strtoupper($auditLog->action) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="40%" class="text-muted">
                                        <i class="bi bi-table me-2"></i>Tabel
                                    </td>
                                    <td><code class="fs-6">{{ $auditLog->table_name }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-globe me-2"></i>IP Address
                                    </td>
                                    <td><code>{{ $auditLog->ip_address }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-browser-chrome me-2"></i>User Agent
                                    </td>
                                    <td>
                                        <small class="font-monospace">{{ $auditLog->user_agent }}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Changes Card -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-arrow-left-right me-2"></i>
                    Perubahan Data
                </h5>
            </div>
            <div class="card-body">
                @if($auditLog->action === 'created' && $auditLog->new_values)
                    <!-- Created - Show New Values Only -->
                    <div class="alert alert-success">
                        <i class="bi bi-plus-circle me-2"></i>
                        <strong>Data Baru Dibuat</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th width="30%">Field</th>
                                    <th width="70%">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLog->new_values as $field => $value)
                                    <tr>
                                        <td class="fw-bold">{{ $field }}</td>
                                        <td>
                                            @if(is_null($value))
                                                <em class="text-muted">NULL</em>
                                            @elseif(is_bool($value))
                                                <span class="badge bg-{{ $value ? 'success' : 'secondary' }}">
                                                    {{ $value ? 'TRUE' : 'FALSE' }}
                                                </span>
                                            @elseif(is_array($value))
                                                <pre class="mb-0 bg-light p-2 rounded"><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                            @else
                                                <code>{{ $value }}</code>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($auditLog->action === 'deleted' && $auditLog->old_values)
                    <!-- Deleted - Show Old Values Only -->
                    <div class="alert alert-danger">
                        <i class="bi bi-trash me-2"></i>
                        <strong>Data Dihapus</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-danger">
                                <tr>
                                    <th width="30%">Field</th>
                                    <th width="70%">Nilai Sebelum Dihapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLog->old_values as $field => $value)
                                    <tr>
                                        <td class="fw-bold">{{ $field }}</td>
                                        <td>
                                            @if(is_null($value))
                                                <em class="text-muted">NULL</em>
                                            @elseif(is_bool($value))
                                                <span class="badge bg-{{ $value ? 'success' : 'secondary' }}">
                                                    {{ $value ? 'TRUE' : 'FALSE' }}
                                                </span>
                                            @elseif(is_array($value))
                                                <pre class="mb-0 bg-light p-2 rounded"><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                            @else
                                                <code>{{ $value }}</code>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($auditLog->action === 'updated' && $auditLog->old_values && $auditLog->new_values)
                    <!-- Updated - Show Comparison -->
                    <div class="alert alert-warning">
                        <i class="bi bi-pencil me-2"></i>
                        <strong>Data Diperbarui</strong> - Perbandingan nilai lama dan baru
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-warning">
                                <tr>
                                    <th width="25%">Field</th>
                                    <th width="37.5%">
                                        <i class="bi bi-arrow-left me-1"></i>
                                        Nilai Lama
                                    </th>
                                    <th width="37.5%">
                                        <i class="bi bi-arrow-right me-1"></i>
                                        Nilai Baru
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Merge all fields from old and new values
                                    $allFields = array_unique(array_merge(
                                        array_keys($auditLog->old_values),
                                        array_keys($auditLog->new_values)
                                    ));
                                @endphp
                                @foreach($allFields as $field)
                                    @php
                                        $oldValue = $auditLog->old_values[$field] ?? null;
                                        $newValue = $auditLog->new_values[$field] ?? null;
                                        $hasChanged = $oldValue !== $newValue;
                                    @endphp
                                    <tr class="{{ $hasChanged ? 'table-warning bg-opacity-25' : '' }}">
                                        <td class="fw-bold">
                                            {{ $field }}
                                            @if($hasChanged)
                                                <i class="bi bi-exclamation-circle text-warning ms-1" title="Berubah"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_null($oldValue))
                                                <em class="text-muted">NULL</em>
                                            @elseif(is_bool($oldValue))
                                                <span class="badge bg-{{ $oldValue ? 'success' : 'secondary' }}">
                                                    {{ $oldValue ? 'TRUE' : 'FALSE' }}
                                                </span>
                                            @elseif(is_array($oldValue))
                                                <pre class="mb-0 bg-light p-2 rounded"><code>{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</code></pre>
                                            @else
                                                <code>{{ $oldValue }}</code>
                                            @endif
                                        </td>
                                        <td class="{{ $hasChanged ? 'bg-warning bg-opacity-10' : '' }}">
                                            @if(is_null($newValue))
                                                <em class="text-muted">NULL</em>
                                            @elseif(is_bool($newValue))
                                                <span class="badge bg-{{ $newValue ? 'success' : 'secondary' }}">
                                                    {{ $newValue ? 'TRUE' : 'FALSE' }}
                                                </span>
                                            @elseif(is_array($newValue))
                                                <pre class="mb-0 bg-light p-2 rounded"><code>{{ json_encode($newValue, JSON_PRETTY_PRINT) }}</code></pre>
                                            @else
                                                <code>{{ $newValue }}</code>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i class="bi bi-info-circle me-2"></i>
                        Tidak ada data perubahan tersimpan
                    </div>
                @endif
            </div>
        </div>

        <!-- Raw Data Card (for debugging) -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">
                    <i class="bi bi-code-square me-2"></i>
                    Raw Data (JSON)
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($auditLog->old_values)
                        <div class="col-md-6">
                            <h6 class="text-danger">Old Values:</h6>
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    @endif
                    @if($auditLog->new_values)
                        <div class="col-md-{{ $auditLog->old_values ? '6' : '12' }}">
                            <h6 class="text-success">New Values:</h6>
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
