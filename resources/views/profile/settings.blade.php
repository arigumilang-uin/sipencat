@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Kelola akun dan preferensi Anda')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Quick Actions -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <i class="bi bi-pencil-square fs-1 text-primary"></i>
                                    <h6 class="mt-2">Edit Profile</h6>
                                    <p class="text-muted small mb-0">Ubah nama, username, email</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('profile.password.edit') }}" class="text-decoration-none">
                            <div class="card border-warning h-100">
                                <div class="card-body">
                                    <i class="bi bi-key fs-1 text-warning"></i>
                                    <h6 class="mt-2">Ganti Password</h6>
                                    <p class="text-muted small mb-0">Perbarui password akun Anda</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Akun</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="30%" class="text-muted"><strong>Nama Lengkap</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Username</strong></td>
                        <td><code>{{ $user->username }}</code></td>
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
                        <td class="text-muted"><strong>Status</strong></td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>Member Since</strong></td>
                        <td>{{ $user->created_at->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Zona Berbahaya</h5>
            </div>
            <div class="card-body">
                <h6 class="text-danger">Nonaktifkan Akun</h6>
                <p class="text-muted mb-3">
                    Menonaktifkan akun akan mencegah Anda login ke sistem. Hanya administrator yang dapat mengaktifkan kembali akun Anda.
                </p>

                @if($user->isAdmin())
                    @php
                        $activeAdminCount = App\Models\User::where('role', 'admin')->where('is_active', true)->count();
                    @endphp
                    @if($activeAdminCount <= 1)
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle me-2"></i>
                            Anda adalah satu-satunya admin aktif. Tidak dapat menonaktifkan akun.
                        </div>
                    @else
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                            <i class="bi bi-x-circle me-1"></i> Nonaktifkan Akun Saya
                        </button>
                    @endif
                @else
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                        <i class="bi bi-x-circle me-1"></i> Nonaktifkan Akun Saya
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- System Info -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Sistem</h6>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>SIPENCAT</strong></p>
                <p class="small text-muted mb-2">Secure Inventory System</p>
                <p class="small text-muted mb-0">Version 1.0.0</p>
            </div>
        </div>

        <!-- Help -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-question-circle me-2"></i>Bantuan</h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">Butuh bantuan?</p>
                <ul class="small mb-0">
                    <li>Hubungi Administrator</li>
                    <li>Baca dokumentasi sistem</li>
                    <li>Lihat tutorial penggunaan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Nonaktifkan Akun</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.deactivate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Perhatian!</strong> Tindakan ini akan:
                        <ul class="mb-0 mt-2">
                            <li>Mencegah Anda login ke sistem</li>
                            <li>Memerlukan admin untuk mengaktifkan kembali</li>
                            <li>Logout dari sesi saat ini</li>
                        </ul>
                    </div>

                    <p>Untuk konfirmasi, masukkan password Anda:</p>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Nonaktifkan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
