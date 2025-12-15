@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Perbarui informasi dan hak akses pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">Edit User: {{ $user->username }}</h1>
        <a href="{{ route('admin.users.index') }}" class="group inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="bi bi-arrow-left mr-2 transition-transform duration-200 group-hover:-translate-x-1"></i>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6 sm:p-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- User Identity Section -->
                <div>
                    <h3 class="text-base font-bold text-indigo-900 border-b border-indigo-100 pb-3 mb-6 flex items-center">
                        <i class="bi bi-person-badge mr-2 text-indigo-500"></i>
                        Identitas Pengguna
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nama -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('name') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                                   placeholder="Contoh: Budi Santoso" 
                                   required>
                            @error('name')
                                <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="sm:col-span-2">
                            <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Username <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="bi bi-at text-slate-400"></i>
                                </div>
                                <input type="text" 
                                       name="username" 
                                       id="username" 
                                       value="{{ old('username', $user->username) }}"
                                       class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('username') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                                       placeholder="username_unik" 
                                       required>
                            </div>
                            @error('username')
                                <p class="mt-1 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div>
                    <h3 class="text-base font-bold text-indigo-900 border-b border-indigo-100 pb-3 mb-6 flex items-center">
                        <i class="bi bi-shield-lock mr-2 text-indigo-500"></i>
                        Keamanan & Akses
                    </h3>

                    <div class="mb-6 rounded-xl bg-blue-50 border border-blue-100 p-4">
                        <div class="flex gap-3">
                            <i class="bi bi-info-circle-fill text-blue-500 text-lg"></i>
                            <div>
                                <h4 class="text-sm font-bold text-blue-900">Ubah Password</h4>
                                <p class="text-xs text-blue-700 mt-1">Kosongkan kolom password jika Anda tidak ingin mengubah password user ini.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Password Baru
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" 
                                       name="password" 
                                       id="password" 
                                       class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('password') border-rose-300 bg-rose-50 text-rose-900 @enderror"
                                       placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-500 focus:outline-none">
                                    <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Minimal 6 karakter.</p>
                            @error('password')
                                <p class="mt-1 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Konfirmasi Password
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                   placeholder="••••••••">
                        </div>

                        <!-- Role -->
                        <div class="sm:col-span-2">
                            <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Role Pengguna <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach($roles as $role)
                                    <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 shadow-sm focus:outline-none ring-1 ring-transparent hover:ring-indigo-200 transition-all duration-200 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:ring-indigo-500 @error('role') border-rose-300 bg-rose-50 @enderror">
                                        <input type="radio" name="role" value="{{ $role->value }}" class="sr-only" {{ old('role', $user->role->value) === $role->value ? 'checked' : '' }} required>
                                        <div class="flex flex-col w-full text-center">
                                            <span class="font-bold text-sm text-slate-900">{{ $role->label() }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('role')
                                <p class="mt-2 text-xs text-rose-500 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Switch -->
                        <div class="sm:col-span-2">
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 p-4 border border-slate-200">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900">Status Aktif</span>
                                    <span class="text-xs text-slate-500">{{ $user->is_active ? 'User saat ini dapat login ke sistem' : 'User saat ini TIDAK BISA login ke sistem' }}</span>
                                </div>
                                <div x-data="{ active: {{ old('is_active', $user->is_active) ? 'true' : 'false' }} }">
                                    <input type="hidden" name="is_active" :value="active ? 1 : 0">
                                    <button type="button" 
                                            @click="active = !active" 
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2" 
                                            :class="active ? 'bg-indigo-600' : 'bg-slate-200'" 
                                            role="switch" 
                                            :aria-checked="active">
                                        <span aria-hidden="true" 
                                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" 
                                              :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-600 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="bi bi-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Warning Card -->
    <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <i class="bi bi-exclamation-triangle-fill text-xl text-amber-500"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-amber-900 mb-1">Perhatian Perubahan User</h4>
                <ul class="text-sm text-amber-700 space-y-1 list-disc list-inside marker:text-amber-400">
                    <li>Mengubah <strong>Role</strong> akan langsung mengubah hak akses dan menu yang tersedia bagi user tersebut.</li>
                    <li>Menonaktifkan user akan memutus sesi login aktif dan mencegah login baru.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
