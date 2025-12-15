<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIPENCAT</title>
    
    <!-- Fonts: Plus Jakarta Sans (Modern & Geometric) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc; /* Slate-50 */
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.02); 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }

        /* Smooth Entry Animation */
        .animate-enter {
            animation: enter 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(15px);
        }
        @keyframes enter {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Staggered Delay for children */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        
        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-slate-600 bg-slate-50" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (User Only) -->
        @auth
            @include('layouts.sidebar')
        @endauth

        <!-- Main Content -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden transition-all duration-300 @auth lg:ml-72 @endauth">
            
            <!-- Navbar (User Only) -->
            @auth
                @include('layouts.navbar')
            @endauth

            <!-- Page Content -->
            <main class="w-full flex-grow {{ Auth::check() ? 'p-6 lg:p-10' : '' }} animate-enter">
                
                {{-- Flash Messages with Alpine --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms 
                         class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm flex items-start animate-enter">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-emerald-800 text-base">{{ session('success') }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="bg-transparent rounded-md inline-flex text-emerald-500 hover:text-emerald-600 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <i class="bi bi-x text-xl"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms 
                         class="mb-6 rounded-2xl bg-rose-50 border border-rose-100 p-4 shadow-sm flex items-start animate-enter">
                        <div class="flex-shrink-0">
                            <i class="bi bi-x-circle-fill text-rose-500 text-xl"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-rose-800 text-base">{{ session('error') }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="bg-transparent rounded-md inline-flex text-rose-500 hover:text-rose-600 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <i class="bi bi-x text-xl"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                
                <!-- Page Title Section removed to prevent duplication with Navbar -->


                @yield('content')
                
                <!-- Footer (User Only) -->
                @auth
                    <div class="mt-12 pt-6 border-t border-slate-200 text-center text-slate-400 text-sm animate-enter delay-300">
                        &copy; {{ date('Y') }} SIPENCAT - Sistem Pengamanan & Catatan Aset Terpadu
                    </div>
                @endauth
            </main>
        </div>
        
        <!-- Mobile Sidebar Overlay (User Only) -->
        @auth
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
                 class="fixed inset-0 z-20 bg-slate-900/50 backdrop-blur-sm transition-opacity lg:hidden"></div>
        @endauth
             
    </div>

    @stack('scripts')
</body>
</html>
