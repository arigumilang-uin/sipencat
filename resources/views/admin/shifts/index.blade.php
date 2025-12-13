@extends('layouts.app')

@section('title', 'Kelola Shift')
@section('page-title', 'Kelola Shift')
@section('page-subtitle', 'Manajemen shift untuk Staff Operasional')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Info Card -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informasi:</strong> Shift memudahkan pengelompokan Staff Operasional dan pengaturan jam kerja secara massal.
            Satu user hanya bisa terdaftar di satu shift.
        </div>

        <!-- Create Shift Form -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Shift Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.shifts.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Shift <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Contoh: Shift Pagi" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <input type="text" name="description" class="form-control" 
                                       placeholder="Contoh: Shift pagi 08:00-17:00" value="{{ old('description') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="is_active" class="form-select" required>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Available Users (Not in any shift) -->
        @if($availableUsers->count() > 0)
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>
                        Staff Operasional Tersedia ({{ $availableUsers->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">User berikut belum terdaftar di shift manapun:</p>
                    <div class="row">
                        @foreach($availableUsers as $user)
                            <div class="col-md-3 mb-2">
                                <span class="badge bg-secondary">
                                    <i class="bi bi-person"></i> {{ $user->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- List Shifts -->
        @forelse($shifts as $shift)
            <div class="card mb-3">
                <div class="card-header bg-{{ $shift->is_active ? 'primary' : 'secondary' }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-people-fill me-2"></i>
                            {{ $shift->name }}
                            <span class="badge bg-light text-dark ms-2">{{ $shift->members_count }} Anggota</span>
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <form action="{{ route('admin.shifts.toggle', $shift) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-{{ $shift->is_active ? 'warning' : 'success' }}" 
                                        title="{{ $shift->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi bi-toggle-{{ $shift->is_active ? 'off' : 'on' }}"></i>
                                    {{ $shift->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" 
                                  class="d-inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus shift {{ $shift->name }}?\nPastikan tidak ada anggota di shift ini.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Hapus Shift">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($shift->description)
                        <p class="text-muted mb-3">
                            <i class="bi bi-info-circle me-1"></i> {{ $shift->description }}
                        </p>
                    @endif

                    <!-- Add Member Form -->
                    <div class="border rounded p-3 mb-3 bg-light">
                        <form action="{{ route('admin.shifts.add-member', $shift) }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-auto">
                                <label class="form-label fw-bold mb-0">Tambah Anggota:</label>
                            </div>
                            <div class="col-md-6">
                                <select name="user_id" class="form-select form-select-sm" required>
                                    <option value="">Pilih Staff Operasional...</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-person-plus"></i> Tambahkan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Member List -->
                    @if($shift->members->count() > 0)
                        <h6 class="fw-bold mb-2">Anggota Shift:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Nama</th>
                                        <th width="25%">Username</th>
                                        <th width="20%">Email</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shift->members as $index => $member)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <i class="bi bi-person-circle me-1"></i>
                                                <strong>{{ $member->name }}</strong>
                                            </td>
                                            <td><code>{{ $member->username }}</code></td>
                                            <td>{{ $member->email ?? '-' }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.shifts.remove-member', [$shift, $member]) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Hapus {{ $member->name }} dari {{ $shift->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus dari shift">
                                                        <i class="bi bi-person-x"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Shift ini belum memiliki anggota. Tambahkan Staff Operasional ke shift ini.
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada shift yang dibuat. Buat shift pertama Anda di atas!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
