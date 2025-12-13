<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shield-check fs-1 mb-2"></i>
        <h4>SIPENCAT</h4>
        <small class="d-block text-white-50">Sistem Aset Terpadu</small>
    </div>

    <nav class="sidebar-menu">
        <!-- Dashboard - Semua Role -->
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <!-- ADMIN MENU -->
        @can('isAdmin')
            <div class="mt-3 px-3">
                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Admin</small>
            </div>

            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Kelola User</span>
            </a>

            <a href="{{ route('admin.audit-logs.index') }}" class="{{ request()->routeIs('admin.audit-logs*') ? 'active' : '' }}">
                <i class="bi bi-file-text-fill"></i>
                <span>Audit Logs</span>
            </a>
        @endcan

        <!-- INVENTORY MENU (Admin & Gudang) -->
        @can('canManageInventory')
            <div class="mt-3 px-3">
                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Master Data</small>
            </div>

            <a href="{{ route('inventory.barang.index') }}" class="{{ request()->routeIs('inventory.barang*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Data Barang</span>
            </a>

            <a href="{{ route('inventory.supplier.index') }}" class="{{ request()->routeIs('inventory.supplier*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Data Supplier</span>
            </a>

            <div class="mt-3 px-3">
                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Transaksi</small>
            </div>

            <a href="{{ route('inventory.barang-masuk.index') }}" class="{{ request()->routeIs('inventory.barang-masuk*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-down"></i>
                <span>Barang Masuk</span>
            </a>

            <a href="{{ route('inventory.barang-keluar.index') }}" class="{{ request()->routeIs('inventory.barang-keluar*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-up"></i>
                <span>Barang Keluar</span>
            </a>
        @endcan

        <!-- REPORTS MENU (Semua User) -->
        @can('canViewReports')
            <div class="mt-3 px-3">
                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Laporan</small>
            </div>

            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i>
                <span>Dashboard Laporan</span>
            </a>

            @can('canManageInventory')
                {{-- Admin & Gudang: Operational Reports --}}
                <a href="{{ route('reports.stock') }}" class="{{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Laporan Stok</span>
                </a>
                
                <a href="{{ route('reports.mutation') }}" class="{{ request()->routeIs('reports.mutation') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Mutasi Stok</span>
                </a>
            @endcan

            @can('isPemilik')
                {{-- Pemilik: Business Reports Only --}}
                <a href="{{ route('reports.stock') }}" class="{{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Laporan Stok</span>
                </a>
                
                <a href="{{ route('reports.supplier') }}" class="{{ request()->routeIs('reports.supplier') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Laporan Supplier</span>
                </a>
            @endcan
        @endcan

        <!-- User Info at Bottom -->
        <div class="mt-auto px-3 py-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill text-primary fs-5"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <div class="fw-bold small">{{ Auth::user()->name }}</div>
                    <div class="text-white-50" style="font-size: 0.75rem;">
                        {{ Auth::user()->role->label() }}
                    </div>
                </div>
            </div>
        </div>
    </nav>
</aside>
