@extends('layouts.app')

@section('title', 'Profile Saya')
@section('page-title', 'Profile Saya')
@section('page-subtitle', 'Informasi akun pengguna Anda')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: User Card -->
    <div class="space-y-6">
        <div class="rounded-3xl bg-white p-8 shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] text-center ring-1 ring-slate-100/50 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
            
            <div class="relative z-10 -mt-4">
                <div class="mx-auto h-24 w-24 rounded-full bg-white p-1.5 shadow-xl ring-2 ring-indigo-50">
                    <div class="h-full w-full rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold uppercase">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                </div>
                
                <h3 class="mt-4 text-xl font-bold text-slate-800">{{ $user->name }}</h3>
                <p class="text-sm text-slate-500 font-medium">{{ '@' . $user->username }}</p>
                
                <div class="mt-4 flex flex-wrap justify-center gap-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $user->role->value === 'admin' ? 'bg-rose-50 text-rose-700 ring-rose-600/20' : ($user->role->value === 'gudang' ? 'bg-indigo-50 text-indigo-700 ring-indigo-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20') }}">
                        {{ $user->role->label() }}
                    </span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-slate-50 text-slate-700 ring-slate-600/20' }}">
                        {{ $user->is_active ? 'Akun Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3">
                <a href="{{ route('profile.edit') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5">
                    <i class="bi bi-pencil mr-2"></i> Edit
                </a>
                <a href="{{ route('profile.settings') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200 transition-all duration-200">
                    <i class="bi bi-gear mr-2"></i> Settings
                </a>
            </div>
            <div class="mt-3">
                 <a href="{{ route('profile.password.edit') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-amber-50 px-4 py-2.5 text-sm font-bold text-amber-700 hover:bg-amber-100 transition-all duration-200 border border-amber-100">
                    <i class="bi bi-key mr-2"></i> Ganti Password
                </a>
            </div>
        </div>
        
        <!-- Activity Summary Widget -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100/50">
            <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center">
                 <i class="bi bi-activity text-indigo-500 mr-2"></i> Ringkasan Aktivitas
            </h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-emerald-50 p-4 text-center border border-emerald-100">
                     <i class="bi bi-box-arrow-in-down text-2xl text-emerald-600 mb-1 block"></i>
                     <span class="block text-2xl font-bold text-emerald-700">{{ $user->barangMasuk()->count() }}</span>
                     <span class="text-[10px] font-bold uppercase text-emerald-600/70 tracking-wider">Barang Masuk</span>
                </div>
                <div class="rounded-2xl bg-rose-50 p-4 text-center border border-rose-100">
                     <i class="bi bi-box-arrow-up text-2xl text-rose-600 mb-1 block"></i>
                     <span class="block text-2xl font-bold text-rose-700">{{ $user->barangKeluar()->count() }}</span>
                     <span class="text-[10px] font-bold uppercase text-rose-600/70 tracking-wider">Barang Keluar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Details -->
    <div class="col-span-1 lg:col-span-2 space-y-6">
        <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
             <div class="border-b border-slate-100 px-8 py-5 flex items-center gap-3 bg-slate-50/50">
                 <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                     <i class="bi bi-person-lines-fill text-xl"></i>
                 </div>
                 <div>
                     <h3 class="text-base font-bold text-slate-800">Detail Informasi</h3>
                     <p class="text-xs text-slate-500">Data lengkap akun pengguna</p>
                 </div>
             </div>
             
             <div class="p-8">
                 <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-8">
                     <div class="sm:col-span-1">
                         <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Lengkap</dt>
                         <dd class="text-base font-medium text-slate-800">{{ $user->name }}</dd>
                     </div>
                     
                     <div class="sm:col-span-1">
                         <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Username</dt>
                         <dd class="text-base font-medium text-slate-800 flex items-center gap-2">
                             {{ $user->username }}
                             <i class="bi bi-check-circle-fill text-emerald-500 text-xs" title="Verified"></i>
                         </dd>
                     </div>
                     
                     <div class="sm:col-span-1">
                         <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Address</dt>
                         <dd class="text-base font-medium text-slate-800">{{ $user->email ?? '-' }}</dd>
                     </div>
                     
                     <div class="sm:col-span-1">
                         <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Hak Akses / Role</dt>
                         <dd class="mt-1">
                             <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold bg-slate-100 text-slate-700">
                                 {{ $user->role->label() }}
                             </span>
                         </dd>
                     </div>
                     
                     <div class="sm:col-span-2 border-t border-slate-100 pt-6 mt-2"></div>
                     
                     <div class="sm:col-span-1">
                         <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Bergabung Sejak</dt>
                         <dd class="text-sm font-medium text-slate-600">{{ $user->created_at->format('d F Y') }}</dd>
                     </div>
                     
                     <div class="sm:col-span-1">
                         <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Terakhir Login</dt>
                         <dd class="text-sm font-medium text-slate-600">
                             @if($user->last_login_at)
                                 {{ $user->last_login_at->diffForHumans() }}
                                 <span class="text-xs text-slate-400 block font-mono mt-0.5">{{ $user->last_login_at->format('d/m/Y H:i') }}</span>
                             @else
                                 Belum pernah login
                             @endif
                         </dd>
                     </div>
                 </dl>
             </div>
        </div>
        
        <div class="rounded-3xl bg-indigo-50 border border-indigo-100 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
             <div class="flex items-center gap-4">
                 <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-indigo-500 shadow-sm">
                     <i class="bi bi-shield-lock-fill text-2xl"></i>
                 </div>
                 <div>
                     <h4 class="font-bold text-indigo-900">Keamanan Akun</h4>
                     <p class="text-sm text-indigo-700/80">Jaga keamanan akun Anda dengan mengganti password secara berkala.</p>
                 </div>
             </div>
             <a href="{{ route('profile.password.edit') }}" class="whitespace-nowrap rounded-xl bg-white px-4 py-2 text-sm font-bold text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-200 hover:bg-indigo-50 transition-all">
                 Review Keamanan
             </a>
        </div>
    </div>
</div>
@endsection
