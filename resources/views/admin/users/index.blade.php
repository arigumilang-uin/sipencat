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
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
                @if($users->count() > 0)
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-16">No</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Username</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last Login</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">{{ $users->firstItem() + $loop->index }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200 mr-3 text-xs">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono text-xs">{{ $user->username }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @php
                                            $roleBadgeColor = match($user->role->value) {
                                                'admin' => 'purple',
                                                'gudang' => 'blue',
                                                'pemilik' => 'amber',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-md bg-{{ $roleBadgeColor }}-50 px-2 py-1 text-xs font-medium text-{{ $roleBadgeColor }}-700 ring-1 ring-inset ring-{{ $roleBadgeColor }}-700/10">
                                            {{ $user->role->label() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                <i class="bi bi-check-circle mr-1.5"></i>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                <i class="bi bi-x-circle mr-1.5"></i>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($user->last_login_at)
                                            <div class="flex items-center">
                                                @php
                                                    $isOnline = $user->last_login_at->gt(now()->subMinutes(5));
                                                @endphp
                                                <span class="relative flex h-2 w-2 mr-2">
                                                    @if($isOnline)
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                    @else
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-gray-400"></span>
                                                    @endif
                                                </span>
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-medium text-gray-900">{{ $user->last_login_at->diffForHumans() }}</span>
                                                    <span class="text-[10px] text-gray-400">{{ $user->last_login_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Belum login</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-50 transition-colors" title="Detail">
                                                <i class="bi bi-eye"></i><span class="sr-only">Detail</span>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-amber-600 hover:text-amber-900 p-1 rounded-md hover:bg-amber-50 transition-colors" title="Edit">
                                                <i class="bi bi-pencil"></i><span class="sr-only">Edit</span>
                                            </a>
                                            
                                            <!-- Toggle Status -->
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-{{ $user->is_active ? 'gray' : 'green' }}-600 hover:text-{{ $user->is_active ? 'gray' : 'green' }}-900 p-1 rounded-md hover:bg-{{ $user->is_active ? 'gray' : 'green' }}-50 transition-colors" 
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                        @if($user->id === auth()->id()) disabled @endif>
                                                    <i class="bi bi-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }}"></i><span class="sr-only">Status</span>
                                                </button>
                                            </form>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors" title="Hapus" @if($user->id === auth()->id()) disabled @endif>
                                                    <i class="bi bi-trash"></i><span class="sr-only">Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-people text-4xl text-gray-300 block mb-3"></i>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada user</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan user baru ke sistem.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Tambah User
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
