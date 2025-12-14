@extends('layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')
@section('page-subtitle', 'Perbarui informasi dasar akun Anda')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="border-b border-slate-100 px-8 py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="bi bi-pencil-square text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Formulir Profile</h3>
                    <p class="text-xs text-slate-500">Update data diri Anda di sini.</p>
                </div>
            </div>
            
            <a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-all duration-200">
                <i class="bi bi-arrow-left mr-2"></i>
                Batal
            </a>
        </div>

        <div class="p-8">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 font-medium text-slate-700 @error('name') border-rose-300 bg-rose-50 text-rose-900 @enderror" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 select-none">@</span>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-8 pr-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 font-mono @error('username') border-rose-300 bg-rose-50 text-rose-900 @enderror" 
                                   value="{{ old('username', $user->username) }}" 
                                   required>
                        </div>
                        @error('username')
                            <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                        @else
                           <p class="mt-1 text-[10px] text-slate-400">Username digunakan untuk login ke sistem.</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('email') border-rose-300 bg-rose-50 text-rose-900 @enderror" 
                                   value="{{ old('email', $user->email) }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="rounded-xl bg-blue-50 border border-blue-100 p-4 flex gap-3">
                         <i class="bi bi-info-circle-fill text-blue-500 mt-0.5"></i>
                         <p class="text-sm text-blue-700 leading-relaxed">
                             Role dan Status Akun hanya dapat diubah oleh Administrator. Hubungi admin jika terjadi kesalahan pada hak akses Anda.
                         </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="bi bi-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
