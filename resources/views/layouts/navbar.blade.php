<nav class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 shadow-sm transition-all duration-300">
    <div class="px-4 sm:px-6 lg:px-10">
        <div class="flex h-20 items-center justify-between">
            <!-- Left Side -->
            <div class="flex items-center gap-6">
                <!-- Mobile menu button -->
                <button type="button" @click="sidebarOpen = true" class="lg:hidden inline-flex items-center justify-center rounded-xl p-2.5 text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors">
                    <span class="sr-only">Open sidebar</span>
                    <i class="bi bi-list text-2xl"></i>
                </button>

                <!-- Page Title -->
                <div class="animate-enter">
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight leading-none bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">
                        @yield('page-title', 'Dashboard')
                    </h1>
                    <p class="text-xs font-medium text-slate-500 hidden sm:block mt-1">
                        @yield('page-subtitle', 'Selamat Datang kembali di SIPENCAT')
                    </p>
                </div>
            </div>

            <!-- Right Side (Actions) -->
            <div class="flex items-center gap-3 sm:gap-6 animate-enter delay-100">
                
                <!-- Time Widget (Optional) -->
                <div class="hidden md:flex flex-col items-end mr-4">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ now()->locale('id')->isoFormat('dddd') }}</span>
                    <span class="text-xs text-slate-400 font-mono">{{ now()->format('d M Y') }}</span>
                </div>
                
                <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    @php
                        $unreadCount = Auth::user()->notifications()->unread()->count();
                        $recentNotifs = Auth::user()->notifications()->latest()->limit(5)->get();
                    @endphp
                    
                    <button @click="open = !open" class="relative group rounded-xl p-2.5 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none transition-all duration-200">
                        <span class="sr-only">Lihat notifikasi</span>
                        <i class="bi bi-bell-fill text-xl group-hover:scale-110 transition-transform duration-200 inline-block"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-2 right-2 h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white animate-pulse"></span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                         class="absolute right-0 mt-4 w-80 sm:w-96 origin-top-right rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 focus:outline-none z-50 overflow-hidden">
                        
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-2 py-1 rounded-md transition-colors">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="max-h-[24rem] overflow-y-auto scrollbar-hide">
                            @forelse($recentNotifs as $notif)
                                <a href="{{ route('notifications.index') }}" class="block px-5 py-4 hover:bg-slate-50 border-b border-slate-50 last:border-0 transition-colors {{ !$notif->is_read ? 'bg-indigo-50/30' : '' }}">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <div class="h-8 w-8 rounded-full bg-{{ $notif->color == 'danger' ? 'rose' : ($notif->color == 'success' ? 'emerald' : ($notif->color == 'warning' ? 'amber' : 'indigo')) }}-100 flex items-center justify-center">
                                                <i class="{{ $notif->icon }} text-{{ $notif->color == 'danger' ? 'rose' : ($notif->color == 'success' ? 'emerald' : ($notif->color == 'warning' ? 'amber' : 'indigo')) }}-600 text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800">{{ $notif->title }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-2 leading-relaxed">{{ $notif->message }}</p>
                                            <p class="text-[10px] uppercase font-bold text-slate-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if(!$notif->is_read)
                                            <div class="h-2 w-2 rounded-full bg-indigo-500 mt-2"></div>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="px-5 py-12 text-center text-slate-400">
                                    <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="bi bi-bell-slash text-2xl text-slate-300"></i>
                                    </div>
                                    <span class="text-sm font-medium">Tidak ada notifikasi baru</span>
                                </div>
                            @endforelse
                        </div>
                        
                        <div class="bg-slate-50 px-4 py-3 text-center border-t border-slate-100">
                            <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua</a>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative ml-2" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 rounded-full bg-white p-1 pr-4 text-sm focus:outline-none ring-1 ring-slate-200 hover:ring-indigo-300 shadow-sm transition-all hover:bg-slate-50 group">
                        <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 text-white flex items-center justify-center font-bold text-sm shadow-md ring-2 ring-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-bold text-slate-700 leading-none group-hover:text-indigo-700 transition-colors">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wide">{{ Auth::user()->role->label() }}</p>
                        </div>
                        <i class="bi bi-chevron-down text-xs text-slate-400 group-hover:text-indigo-400 transition-transform duration-200 ml-1" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Profile Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                         class="absolute right-0 mt-4 w-60 origin-top-right rounded-2xl bg-white py-2 shadow-xl ring-1 ring-black/5 focus:outline-none z-50">
                        
                        <div class="px-5 py-4 border-b border-slate-100 md:hidden bg-slate-50/50">
                            <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                        </div>
                        
                        <div class="px-2 py-2">
                            <a href="{{ route('profile.show') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="bi bi-person-circle mr-3 text-slate-400 group-hover:text-indigo-500 text-lg"></i>
                                Profil Saya
                            </a>
                            <a href="{{ route('profile.settings') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="bi bi-sliders mr-3 text-slate-400 group-hover:text-indigo-500 text-lg"></i>
                                Pengaturan
                            </a>
                        </div>
                        
                        <div class="my-1 border-t border-slate-100"></div>
                        
                        <div class="px-2 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium text-rose-600 rounded-xl hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                    <i class="bi bi-box-arrow-right mr-3 text-rose-400 group-hover:text-rose-500 text-lg"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
