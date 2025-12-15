@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-subtitle', 'Manajemen user sistem')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Daftar User</h1>
        <p class="mt-2 text-sm text-gray-700">Daftar lengkap pengguna yang memiliki akses ke sistem SIPENCAT.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.users.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
            <i class="bi bi-plus-circle mr-2"></i>Tambah User
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] overflow-hidden ring-1 ring-slate-100/50">
                @if($users->count() > 0)
                    <table class="min-w-full">
                        <thead class="bg-slate-50/50 border-b border-slate-100/50">
                            <tr>
                                <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 w-16">No</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Nama</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Username</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Role</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Last Login</th>
                                <th scope="col" class="relative py-4 pl-3 pr-6 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($users as $user)
                                <tr class="hover:bg-indigo-50/30 transition-colors duration-200 group">
                                    <td class="whitespace-nowrap py-5 pl-6 pr-3 text-sm text-slate-500 font-medium">{{ $users->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200 mr-4 text-xs shadow-sm">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500 font-mono text-xs">{{ $user->username }}</td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                                        @php
                                            $roleBadgeColor = match($user->role->value) {
                                                'admin' => 'purple',
                                                'gudang' => 'blue',
                                                'pemilik' => 'amber',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-lg bg-{{ $roleBadgeColor }}-50 px-2.5 py-1 text-xs font-bold text-{{ $roleBadgeColor }}-600 ring-1 ring-inset ring-{{ $roleBadgeColor }}-500/10 shadow-sm">
                                            {{ $user->role->label() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600 ring-1 ring-inset ring-emerald-500/10 shadow-sm">
                                                <i class="bi bi-check-circle-fill mr-1.5 text-emerald-500"></i>Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-500 ring-1 ring-inset ring-slate-500/10 shadow-sm">
                                                <i class="bi bi-x-circle-fill mr-1.5 text-slate-400"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500">
                                        @if($user->last_seen_at)
                                            <div class="flex items-center">
                                                @php
                                                    // User is "online" if last seen within 5 minutes
                                                    $isOnline = $user->last_seen_at->gt(now()->subMinutes(5));
                                                @endphp
                                                <span class="relative flex h-2.5 w-2.5 mr-3">
                                                    @if($isOnline)
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-sm ring-2 ring-white"></span>
                                                    @else
                                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-300 ring-2 ring-white"></span>
                                                    @endif
                                                </span>
                                                <div class="flex flex-col">
                                                    @if($isOnline)
                                                        <span class="text-xs font-bold text-emerald-600">● Online</span>
                                                        <span class="text-[10px] text-slate-400 font-medium mt-0.5">Aktif {{ $user->last_seen_at->diffForHumans() }}</span>
                                                    @else
                                                        <span class="text-xs font-bold text-slate-700">Terakhir aktif</span>
                                                        <span class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $user->last_seen_at->diffForHumans() }} • {{ $user->last_seen_at->format('d M, H:i') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($user->last_login_at)
                                            <div class="flex items-center">
                                                <span class="relative flex h-2.5 w-2.5 mr-3">
                                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-300 ring-2 ring-white"></span>
                                                </span>
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-700">Login terakhir</span>
                                                    <span class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $user->last_login_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-xs italic pl-1">Belum pernah aktif</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-center text-sm font-medium">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-200 shadow-sm" title="Detail">
                                                <i class="bi bi-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 shadow-sm" title="Edit">
                                                <i class="bi bi-pencil text-sm"></i>
                                            </a>
                                            
                                            <!-- Toggle Status -->
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                        class="flex items-center justify-center h-8 w-8 rounded-full bg-{{ $user->is_active ? 'slate' : 'emerald' }}-50 text-{{ $user->is_active ? 'slate' : 'emerald' }}-600 hover:bg-{{ $user->is_active ? 'slate' : 'emerald' }}-600 hover:text-white transition-all duration-200 shadow-sm" 
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                        @if($user->id === auth()->id()) disabled @endif>
                                                    <i class="bi bi-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }} text-sm"></i>
                                                </button>
                                            </form>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center justify-center h-8 w-8 rounded-full bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm" title="Hapus" @if($user->id === auth()->id()) disabled @endif>
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="bg-white px-4 py-4 border-t border-slate-100 sm:px-6">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <i class="bi bi-people text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-slate-800">Belum ada user</h3>
                        <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">Sistem belum memiliki data user. Mulai dengan menambahkan user baru sekarang.</p>
                        <div class="mt-8">
                            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg hover:bg-indigo-500 hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-1">
                                <i class="bi bi-plus-circle mr-2 text-lg"></i>
                                Tambah User Baru
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
