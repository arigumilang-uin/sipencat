<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
     class="fixed inset-y-0 z-50 flex w-72 flex-col bg-slate-900 shadow-2xl transition-transform duration-300 ease-spring lg:translate-x-0 lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:w-72 border-r border-slate-800/50">
    
    <!-- Brand Logo -->
    <div class="flex h-20 shrink-0 items-center px-6 bg-slate-900/50 backdrop-blur-sm sticky top-0 z-10">
        <div class="flex items-center gap-3 w-full group">
            <div class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                <i class="bi bi-shield-check text-xl text-white"></i>
                <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-white/20"></div>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400 tracking-tight">SIPENCAT</span>
                <span class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Asset System</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-8 scrollbar-hide">
        
        <!-- Main Group -->
        <div>
            <div class="mb-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500/80">
                Menu Utama
            </div>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="{{ request()->routeIs('dashboard*') 
                        ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' 
                        : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} 
                        group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 select-none">
                    <i class="bi bi-grid-fill w-5 h-5 mr-3 text-lg {{ request()->routeIs('dashboard*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300 transition-colors' }}"></i>
                    Dashboard
                </a>
            </div>
        </div>

        @can('isAdmin')
        <!-- Admin Group -->
        <div class="animate-enter delay-100">
            <div class="mb-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500/80">
                Administrasi
            </div>
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" 
                   class="{{ request()->routeIs('admin.users*') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-people w-5 h-5 mr-3 text-lg"></i>
                    Kelola Pengguna
                </a>
                
                <a href="{{ route('admin.audit-logs.index') }}" 
                   class="{{ request()->routeIs('admin.audit-logs*') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-activity w-5 h-5 mr-3 text-lg"></i>
                    Log Aktivitas
                </a>
                
                <a href="{{ route('admin.working-hours.index') }}" 
                   class="{{ request()->routeIs('admin.working-hours*') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-clock-history w-5 h-5 mr-3 text-lg"></i>
                    Jam Kerja
                </a>
                
                <a href="{{ route('admin.shifts.index') }}" 
                   class="{{ request()->routeIs('admin.shifts*') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-calendar-range w-5 h-5 mr-3 text-lg"></i>
                    Shift Pegawai
                </a>
                
                <a href="{{ route('admin.overtime.index') }}" 
                   class="{{ request()->routeIs('admin.overtime*') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <div class="flex items-center">
                        <i class="bi bi-hourglass-split w-5 h-5 mr-3 text-lg"></i>
                        <span>Lembur</span>
                    </div>
                    @php $pendingCount = \App\Models\OvertimeRequest::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-500 text-[10px] font-bold text-white shadow-low">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
        @endcan

        <!-- Operation Group - For Staff Operasional -->
        @can('isStaffOperasional')
        <div class="animate-enter delay-150">
            <div class="mb-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500/80">
                Info Saya
            </div>
            <div class="space-y-1">
                <a href="{{ route('shift.info') }}" 
                   class="{{ request()->routeIs('shift.info') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-person-badge-fill w-5 h-5 mr-3 text-lg"></i>
                    Shift Saya
                </a>
            </div>
        </div>
        @endcan

        @can('canManageInventory')
        <!-- Inventory/Operation Group -->
        <div class="animate-enter delay-200">
            <div class="mb-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500/80">
                Operasional
            </div>
            <div class="space-y-1">
            
                <a href="{{ route('inventory.barang.index') }}" 
                   class="{{ request()->routeIs('inventory.barang*') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-box-seam-fill w-5 h-5 mr-3 text-lg"></i>
                    Data Barang
                </a>
                
                <a href="{{ route('inventory.supplier.index') }}" 
                   class="{{ request()->routeIs('inventory.supplier*') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-shop-window w-5 h-5 mr-3 text-lg"></i>
                    Data Supplier
                </a>
            </div>
            
            <div class="mt-8 mb-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500/80">
                Transaksi
            </div>
            <div class="space-y-1">
                <a href="{{ route('inventory.barang-masuk.index') }}" 
                   class="{{ request()->routeIs('inventory.barang-masuk*') ? 'bg-emerald-500/10 text-emerald-400 shadow-sm ring-1 ring-emerald-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-arrow-down-circle-fill w-5 h-5 mr-3 text-lg opacity-70 group-hover:opacity-100"></i>
                    Barang Masuk
                </a>
                
                <a href="{{ route('inventory.barang-keluar.index') }}" 
                   class="{{ request()->routeIs('inventory.barang-keluar*') ? 'bg-rose-500/10 text-rose-400 shadow-sm ring-1 ring-rose-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-arrow-up-circle-fill w-5 h-5 mr-3 text-lg opacity-70 group-hover:opacity-100"></i>
                    Barang Keluar
                </a>
            </div>
        </div>
        @endcan

        @can('canViewReports')
        <!-- Reports Group -->
        <div class="animate-enter delay-300">
            <div class="mb-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500/80">
                Laporan & Analisa
            </div>
            <div class="space-y-1">
                <a href="{{ route('reports.index') }}" 
                   class="{{ request()->routeIs('reports.index') ? 'bg-indigo-500/10 text-indigo-400 shadow-sm ring-1 ring-indigo-500/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }} group flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    <i class="bi bi-bar-chart-fill w-5 h-5 mr-3 text-lg"></i>
                    Pusat Laporan
                </a>
                
                @if(Gate::allows('canManageInventory') || Gate::allows('isPemilik'))
                    <a href="{{ route('reports.stock') }}" class="text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 group flex items-center rounded-xl px-4 py-2 text-sm font-medium transition-all duration-200 ml-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-3 group-hover:bg-indigo-400 transition-colors"></span>
                        Stok Barang
                    </a>
                    <a href="{{ route('reports.supplier') }}" class="text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 group flex items-center rounded-xl px-4 py-2 text-sm font-medium transition-all duration-200 ml-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-3 group-hover:bg-indigo-400 transition-colors"></span>
                        Performa Supplier
                    </a>
                @endif
            </div>
        </div>
        @endcan

    </nav>
    
    <!-- User Profile (Bottom) -->
    <div class="border-t border-slate-800/50 p-4 bg-slate-900/50 backdrop-blur-sm sticky bottom-0">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-800 transition-colors cursor-pointer group">
            <div class="h-10 w-10 flex-shrink-0">
                <div class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white font-bold shadow-lg text-sm border-2 border-slate-700">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->role->label() }}</p>
            </div>
            <i class="bi bi-box-arrow-right text-slate-500 group-hover:text-rose-400 transition-colors" onclick="document.getElementById('logout-form').submit()"></i>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
