<nav class="top-navbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-link d-md-none me-3" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div>
            <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            <small class="text-muted">@yield('page-subtitle', 'Selamat datang di SIPENCAT')</small>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Notifications -->
        <div class="dropdown">
            @php
                $unreadCount = Auth::user()->notifications()->unread()->count();
                $recentNotifs = Auth::user()->notifications()->latest()->limit(5)->get();
            @endphp
            <button class="btn btn-link position-relative" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5 text-dark"></i>
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
                <li class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Notifikasi</span>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0">Tandai Semua</button>
                        </form>
                    @endif
                </li>
                <li><hr class="dropdown-divider"></li>
                @forelse($recentNotifs as $notif)
                    <li>
                        <a class="dropdown-item {{ !$notif->is_read ? 'bg-light' : '' }}" href="{{ route('notifications.index') }}">
                            <div class="d-flex">
                                <i class="{{ $notif->icon }} text-{{ $notif->color }} me-2 fs-5"></i>
                                <div class="flex-grow-1">
                                    <strong class="d-block">{{ $notif->title }}</strong>
                                    <small class="text-muted">{{ $notif->message }}</small>
                                    <small class="d-block text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    @if(!$loop->last)
                        <li><hr class="dropdown-divider"></li>
                    @endif
                @empty
                    <li class="px-3 py-2 text-muted text-center">
                        <small>Tidak ada notifikasi baru</small>
                    </li>
                @endforelse
                @if($recentNotifs->count() > 0)
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center small" href="{{ route('notifications.index') }}">Lihat Semua Notifikasi</a></li>
                @endif
            </ul>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown">
            <button class="btn btn-link d-flex align-items-center text-decoration-none" type="button" data-bs-toggle="dropdown">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="text-start d-none d-md-block">
                    <div class="fw-bold text-dark small">{{ Auth::user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->role->label() }}</div>
                </div>
                <i class="bi bi-chevron-down ms-2 text-dark"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-header">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <small class="text-muted">{{ Auth::user()->username }}</small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.settings') }}">
                        <i class="bi bi-gear me-2"></i> Pengaturan
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
@endpush
