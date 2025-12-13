@extends('layouts.app')

@section('title', 'Informasi Shift Saya')
@section('page-title', 'Shift Saya')
@section('page-subtitle', 'Lihat informasi shift dan jadwal kerja Anda')

@section('content')
<div class="container-fluid">
    @if(!$hasShift)
        {{-- No Shift Assigned --}}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-warning">
                    <h5><i class="bi bi-exclamation-triangle me-2"></i> Belum Ada Shift</h5>
                    <p class="mb-0">{{ $message }}</p>
                </div>
            </div>
        </div>
    @else
        {{-- Shift Information --}}
        <div class="row">
            <div class="col-md-12">
                {{-- Shift Header Card --}}
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-2">
                                    <i class="bi bi-people-fill text-primary me-2"></i>
                                    {{ $shift->name }}
                                </h3>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ $shift->description ?? 'Tidak ada deskripsi' }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge bg-{{ $shift->is_active ? 'success' : 'secondary' }} fs-6 px-3 py-2">
                                    <i class="bi bi-{{ $shift->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                    {{ $shift->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Current Status Alert --}}
                <div class="alert alert-{{ $currentStatus['color'] }} mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock-fill fs-4 me-3"></i>
                        <div>
                            <strong>Status Saat Ini:</strong> {{ $currentStatus['message'] }}
                            <br><small>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Working Hours Schedule --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-week me-2"></i>
                            Jadwal Jam Kerja
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($workingHours->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40%">Hari</th>
                                            <th width="25%">Jam Mulai</th>
                                            <th width="25%">Jam Selesai</th>
                                            <th width="10%">Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workingHours as $wh)
                                            @php
                                                $isToday = strtolower(now()->format('l')) === $wh->day_of_week;
                                                $start = \Carbon\Carbon::parse($wh->start_time);
                                                $end = \Carbon\Carbon::parse($wh->end_time);
                                                $duration = $start->diffInHours($end);
                                            @endphp
                                            <tr class="{{ $isToday ? 'table-active fw-bold' : '' }}">
                                                <td>
                                                    @if($isToday)
                                                        <i class="bi bi-arrow-right-circle-fill text-primary me-2"></i>
                                                    @endif
                                                    {{ $wh->day_name }}
                                                    @if($isToday)
                                                        <span class="badge bg-primary ms-2">Hari Ini</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $start->format('H:i') }}
                                                </td>
                                                <td>
                                                    <i class="bi bi-clock-fill me-1"></i>
                                                    {{ $end->format('H:i') }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $duration }} jam</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                <p class="mb-0">Belum ada jadwal jam kerja untuk shift ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Shift Members --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>
                            Anggota Shift ({{ $members->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($members as $member)
                                <div class="list-group-item {{ $member->id === auth()->id() ? 'bg-light' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-{{ $member->id === auth()->id() ? 'primary' : 'secondary' }} text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">
                                                {{ $member->name }}
                                                @if($member->id === auth()->id())
                                                    <span class="badge bg-primary ms-1">Anda</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-envelope me-1"></i>
                                                {{ $member->email }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted">
                                    Tidak ada anggota
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Statistik Shift</h6>
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-2">
                                    <h4 class="mb-0 text-primary">{{ $members->count() }}</h4>
                                    <small class="text-muted">Anggota</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-2">
                                    <h4 class="mb-0 text-success">{{ $workingHours->count() }}</h4>
                                    <small class="text-muted">Hari Kerja</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="border rounded p-2">
                                    <h4 class="mb-0 text-info">
                                        {{ $workingHours->sum(function($wh) {
                                            $start = \Carbon\Carbon::parse($wh->start_time);
                                            $end = \Carbon\Carbon::parse($wh->end_time);
                                            return $start->diffInHours($end);
                                        }) }}
                                    </h4>
                                    <small class="text-muted">Total Jam/Minggu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
