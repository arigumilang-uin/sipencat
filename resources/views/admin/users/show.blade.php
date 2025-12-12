@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')
@section('page-subtitle', 'Informasi lengkap user')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- User Info Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Informasi User
                    </h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil me-1"></i>
                            Edit
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Profile Picture Placeholder -->
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-3">
                            <code>{{ $user->username }}</code>
                        </p>
                        @php
                            $roleBadge = match($user->role->value) {
                                'admin' => 'danger',
                                'gudang' => 'primary',
                                'pemilik' => 'success',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $roleBadge }} fs-6 mb-2">
                            {{ $user->role->label() }}
                        </span>
                        <br>
                        @if($user->is_active)
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6">
                                <i class="bi bi-x-circle me-1"></i>Nonaktif
                            </span>
                        @endif
                    </div>

                    <!-- User Details -->
                    <div class="col-md-8">
                        <h6 class="border-bottom pb-2 mb-3">Detail Informasi</h6>
                        
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="40%" class="text-muted">
                                        <i class="bi bi-person me-2"></i>Nama Lengkap
                                    </td>
                                    <td><strong>{{ $user->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-at me-2"></i>Username
                                    </td>
                                    <td><code>{{ $user->username }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-shield-check me-2"></i>Role
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $roleBadge }}">
                                            {{ $user->role->label() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-toggle-on me-2"></i>Status
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-calendar-plus me-2"></i>Terdaftar
                                    </td>
                                    <td>{{ $user->created_at->format('d F Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        <i class="bi bi-calendar-check me-2"></i>Terakhir Update
                                    </td>
                                    <td>{{ $user->updated_at->format('d F Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Stats -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-box-arrow-in-down text-success fs-1"></i>
                        <h3 class="mt-2 mb-0">{{ $user->barangMasuk()->count() }}</h3>
                        <small class="text-muted">Transaksi Barang Masuk</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-box-arrow-up text-danger fs-1"></i>
                        <h3 class="mt-2 mb-0">{{ $user->barangKeluar()->count() }}</h3>
                        <small class="text-muted">Transaksi Barang Keluar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-clock-history text-primary fs-1"></i>
                        <h3 class="mt-2 mb-0">{{ $user->auditLogs()->count() }}</h3>
                        <small class="text-muted">Aktivitas Tercatat</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-4 border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Aksi
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>
                        Edit User
                    </a>

                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }}"
                                @if($user->id === auth()->id()) disabled @endif>
                            <i class="bi bi-toggle-{{ $user->is_active ? 'off' : 'on' }} me-1"></i>
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.users.destroy', $user) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger"
                                @if($user->id === auth()->id()) disabled @endif>
                            <i class="bi bi-trash me-1"></i>
                            Hapus User
                        </button>
                    </form>
                </div>

                @if($user->id === auth()->id())
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Anda tidak dapat menonaktifkan atau menghapus akun Anda sendiri</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
