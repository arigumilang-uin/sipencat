<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SIPENCAT</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/50">
    <div class="min-h-full flex items-center justify-center px-4 py-16 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-3 mb-4">
                    <div class="relative flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30">
                        <i class="bi bi-shield-check text-3xl text-white"></i>
                        <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-white/20"></div>
                    </div>
                    <div class="flex flex-col items-start">
                        <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 tracking-tight">SIPENCAT</span>
                        <span class="text-xs uppercase tracking-widest text-slate-500 font-semibold">Asset System</span>
                    </div>
                </div>
            </div>

            <!-- Error Content -->
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                <!-- Error Header -->
                <div class="bg-gradient-to-r @yield('gradient', 'from-indigo-500 to-purple-600') px-8 py-8 text-center">
                    <div class="inline-flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm w-20 h-20 mb-4">
                        <i class="bi @yield('icon') text-4xl text-white"></i>
                    </div>
                    <h1 class="text-5xl font-bold text-white mb-2">@yield('code')</h1>
                    <p class="text-xl text-white/90 font-medium">@yield('title')</p>
                </div>

                <!-- Error Body -->
                <div class="px-8 py-10">
                    <div class="text-center space-y-6">
                        <div class="space-y-3">
                            <p class="text-lg text-slate-900 font-medium">@yield('message-title')</p>
                            <p class="text-slate-600 leading-relaxed max-w-md mx-auto">@yield('message-body')</p>
                        </div>

                        @yield('additional-content')

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-6">
                            @yield('action-buttons')
                            
                            @if(!isset($hideHomeButton))
                            <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <i class="bi bi-house-door-fill mr-2"></i>
                                Kembali ke Beranda
                            </a>
                            @endif
                            
                            <button onclick="window.history.back()" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <i class="bi bi-arrow-left mr-2"></i>
                                Kembali ke Halaman Sebelumnya
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center mt-8 text-sm text-slate-500">
                <p>Jika masalah berlanjut, silakan hubungi administrator sistem.</p>
                <p class="mt-2">© {{ date('Y') }} SIPENCAT - Sistem Pengamanan & Catatan Aset Terpadu</p>
            </div>
        </div>
    </div>
</body>
</html>
