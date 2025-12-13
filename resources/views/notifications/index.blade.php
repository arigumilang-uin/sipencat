@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Semua notifikasi Anda')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Semua Notifikasi</h5>
                    @if(auth()->user()->notifications()->unread()->count() > 0)
                        <form action="{{ route('notifications.read-all') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notif)
                    <div class="list-group list-group-flush">
                        <div class="list-group-item {{ !$notif->is_read ? 'bg-light border-start border-primary border-3' : '' }}">
                            <div class="d-flex align-items-start">
                                <i class="{{ $notif->icon }} text-{{ $notif->color }} me-3 fs-3"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $notif->title }}</h6>
                                            <p class="mb-1">{{ $notif->message }}</p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if(!$notif->is_read)
                                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-check"></i> Tandai Dibaca
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Dibaca</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>
            @if($notifications->hasPages())
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
