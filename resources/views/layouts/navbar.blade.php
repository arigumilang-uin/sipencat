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
        <!-- Notifications (Future feature) -->
        <div class="dropdown">
            <button class="btn btn-link position-relative" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5 text-dark"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    0
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                <li class="dropdown-header">Notifikasi</li>
                <li><hr class="dropdown-divider"></li>
                <li class="px-3 py-2 text-muted text-center">
                    <small>Tidak ada notifikasi baru</small>
                </li>
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
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
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
