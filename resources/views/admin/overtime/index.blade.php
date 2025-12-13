@extends('layouts.app')

@section('title', 'Kelola Permintaan Overtime')
@section('page-title', 'Permintaan Perpanjangan Waktu')
@section('page-subtitle', 'Kelola dan tinjau permintaan perpanjangan jam kerja dari staff')

@section('content')
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Menunggu Persetujuan</h6>
                <h2 class="mb-0">{{ $pendingRequests->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-white-50">Sedang Berjalan</h6>
                <h2 class="mb-0">{{ $activeExtensions }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-white-50">Total Riwayat</h6>
                <h2 class="mb-0">{{ $completedRequests->total() }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Pending Requests -->
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
            <i class="bi bi-hourglass-split me-2"></i>
            Menunggu Persetujuan ({{ $pendingRequests->count() }})
        </h5>
    </div>
    <div class="card-body p-0">
        @forelse($pendingRequests as $request)
            <div class="border-bottom p-3">
                <div class="row align-items-start">
                    <div class="col-md-8">
                        <h6 class="mb-1">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ $request->user->name }}
                            @if($request->user->shift()->exists())
                                <span class="badge bg-info">{{ $request->user->shift->first()->name }}</span>
                            @endif
                        </h6>
                        <p class="mb-2">
                            <strong>Alasan:</strong> {{ $request->reason }}
                        </p>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Durasi diminta: <strong>{{ $request->requested_minutes }} menit</strong>
                            • Diajukan {{ $request->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#approveModal{{ $request->id }}">
                                <i class="bi bi-check-lg"></i> Setujui
                            </button>
                            <button type="button" class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $request->id }}">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('admin.overtime.approve', $request) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Setujui Perpanjangan
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <strong>{{ $request->user->name }}</strong> meminta perpanjangan <strong>{{ $request->requested_minutes }} menit</strong>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Durasi Disetujui (menit) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="granted_minutes" class="form-control" 
                                           value="{{ $request->requested_minutes }}" min="5" max="240" required>
                                    <small class="text-muted">Anda dapat mengubah durasi jika diperlukan</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan (opsional)</label>
                                    <textarea name="admin_notes" class="form-control" rows="2" 
                                              placeholder="Contoh: Perpanjangan diberikan untuk menyelesaikan laporan akhir bulan"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg me-1"></i>
                                    Setujui Perpanjangan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('admin.overtime.reject', $request) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Tolak Perpanjangan
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    User akan menerima notifikasi bahwa permintaan ditolak
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Alasan Penolakan <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="admin_notes" class="form-control" rows="3" required
                                              placeholder="Jelaskan mengapa permintaan ditolak. User akan menerima pesan ini."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-lg me-1"></i>
                                    Tolak Permintaan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p class="mb-0">Tidak ada permintaan perpanjangan yang menunggu persetujuan</p>
            </div>
        @endforelse
    </div>
</div>

<!-- History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-clock-history me-2"></i>
            Riwayat Permintaan
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Alasan</th>
                        <th>Diminta</th>
                        <th>Disetujui</th>
                        <th>Status</th>
                        <th>Diproses Oleh</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completedRequests as $request)
                        <tr>
                            <td>
                                <strong>{{ $request->user->name }}</strong>
                                @if($request->user->shift()->exists())
                                    <br><small class="text-muted">{{ $request->user->shift->first()->name }}</small>
                                @endif
                            </td>
                            <td>
                                <small>{{ Str::limit($request->reason, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $request->requested_minutes }}m</span>
                            </td>
                            <td>
                                @if($request->granted_minutes)
                                    <span class="badge bg-success">{{ $request->granted_minutes }}m</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $request->status_color }}">
                                    {{ $request->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($request->approver)
                                    <small>{{ $request->approver->name }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Belum ada riwayat permintaan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($completedRequests->hasPages())
        <div class="card-footer">
            {{ $completedRequests->links() }}
        </div>
    @endif
</div>
@endsection
