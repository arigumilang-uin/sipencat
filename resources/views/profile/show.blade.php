@extends('layouts.app')

@section('title', 'Profile Saya')
@section('page-title', 'Profile Saya')
@section('page-subtitle', 'Informasi akun Anda')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- User Info Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                    <i class="bi bi-person-fill fs-1"></i>
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ '@' . $user->username }}</p>
                <span class="badge bg-{{ $user->role->value === 'admin' ? 'danger' : ($user->role->value === 'gudang' ? 'primary' : 'info') }} mb-3">
                    {{ $user->role->label() }}
                </span>
                
                @if($user->is_active)
                    <p class="mb-0"><span class="badge bg-success">Akun Aktif</span></p>
                @else
                    <p class="mb-0"><span class="badge bg-danger">Akun Nonaktif</span></p>
                @endif

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-warning">
                        <i class="bi bi-key me-1"></i> Ganti Password
                    </a>
                    <a href="{{ route('profile.settings') }}" class="btn btn-secondary">
                        <i class="bi bi-gear me-1"></i> Pengaturan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Profile Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Detail</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%" class="text-muted"><strong>Nama Lengkap</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Username</strong></td>
                        <td><code>{{ $user->username }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Email</strong></td>
                        <td>{{ $user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Role</strong></td>
                        <td>
                            <span class="badge bg-{{ $user->role->value === 'admin' ? 'danger' : ($user->role->value === 'gudang' ? 'primary' : 'info') }}">
                                {{ $user->role->label() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Status Akun</strong></td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Akun Dibuat</strong></td>
                        <td>{{ $user->created_at->format('d F Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Terakhir Diupdate</strong></td>
                        <td>{{ $user->updated_at->format('d F Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Last Login</strong></td>
                        <td>
                            @if($user->last_login_at)
                                <div>
                                    {{ $user->last_login_at->format('d F Y, H:i') }}
                                    <small class="text-muted d-block">({{ $user->last_login_at->diffForHumans() }})</small>
                                    @if($user->last_login_ip)
                                        <small class="text-muted d-block">IP: {{ $user->last_login_ip }}</small>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">Belum pernah login</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="bi bi-box-arrow-in-down fs-1 text-success"></i>
                            <h3 class="mt-2">{{ number_format($user->barangMasuk->count()) }}</h3>
                            <p class="text-muted mb-0">Transaksi Barang Masuk</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="bi bi-box-arrow-up fs-1 text-danger"></i>
                            <h3 class="mt-2">{{ number_format($user->barangKeluar->count()) }}</h3>
                            <p class="text-muted mb-0">Transaksi Barang Keluar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
