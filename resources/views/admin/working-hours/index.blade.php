@extends('layouts.app')

@section('title', 'Kelola Jam Kerja')
@section('page-title', 'Kelola Jam Kerja')
@section('page-subtitle', 'Atur jam kerja untuk setiap role')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Info Card -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informasi:</strong> Atur jam kerja untuk membatasi akses login berdasarkan waktu. Admin tidak terbatas jam kerja (24/7 access). 
            Jika tidak ada aturan untuk hari tertentu, akses akan terbuka penuh.
        </div>

        <!-- Add Working Hour Form -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Jam Kerja</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.working-hours.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Shift Select -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Shift <span class="text-danger">*</span></label>
                                <select name="shift_id" class="form-select @error('shift_id') is-invalid @enderror" required>
                                    <option value="">Pilih Shift</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }} ({{ $shift->members_count }} anggota)
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hari <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                                    <option value="">Pilih Hari</option>
                                    @foreach($days as $dayValue => $dayName)
                                        <option value="{{ $dayValue }}" {{ old('day_of_week') == $dayValue ? 'selected' : '' }}>
                                            {{ $dayName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('day_of_week')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', '08:00') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', '17:00') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="is_active" class="form-select" required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {{-- Role-based working hours removed --}}
        {{-- Staff Operasional: Use shift-based hours only --}}
        {{-- Pemilik: Has 24/7 access by default --}}


        <!-- Shift-Based Working Hours -->
        @if($workingHoursByShift->count() > 0)
            <h4 class="mt-4 mb-3 text-primary">
                <i class="bi bi-people-fill me-2"></i>
                Jam Kerja Berdasarkan Shift
            </h4>
            
            @foreach($workingHoursByShift as $shiftId => $hours)
                @php
                    $shift = $hours->first()->shift;
                @endphp
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Jam Kerja {{ $shift->name }}
                            <span class="badge bg-light text-dark ms-2">{{ $shift->members_count }} Anggota</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Hari</th>
                                        <th width="15%">Jam Mulai</th>
                                        <th width="15%">Jam Selesai</th>
                                        <th width="15%">Durasi</th>
                                        <th width="15%">Status</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hours as $index => $wh)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $wh->day_name }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($wh->start_time)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($wh->end_time)->format('H:i') }}</td>
                                            <td>
                                                @php
                                                    $start = \Carbon\Carbon::parse($wh->start_time);
                                                    $end = \Carbon\Carbon::parse($wh->end_time);
                                                    $duration = $start->diffInHours($end);
                                                @endphp
                                                {{ $duration }} jam
                                            </td>
                                            <td>
                                                @if($wh->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <form action="{{ route('admin.working-hours.toggle', $wh) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-{{ $wh->is_active ? 'secondary' : 'success' }}" 
                                                                title="{{ $wh->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i class="bi bi-toggle-{{ $wh->is_active ? 'off' : 'on' }}"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.working-hours.destroy', $wh) }}" method="POST" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Yakin ingin menghapus aturan jam kerja ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@endsection
