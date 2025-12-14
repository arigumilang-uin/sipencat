@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Kelola preferensi dan keamanan akun Anda')

@section('content')
<div x-data="{ deactivateModalOpen: false }">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('profile.edit') }}" class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-indigo-50 transition-all duration-500 group-hover:bg-indigo-100"></div>
                    <div class="relative z-10">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Edit Profile</h3>
                        <p class="mt-1 text-sm text-slate-500">Ubah nama, username, dan email.</p>
                    </div>
                </a>

                <a href="{{ route('profile.password.edit') }}" class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-amber-50 transition-all duration-500 group-hover:bg-amber-100"></div>
                    <div class="relative z-10">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-colors group-hover:bg-amber-500 group-hover:text-white">
                            <i class="bi bi-key-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Ganti Password</h3>
                        <p class="mt-1 text-sm text-slate-500">Perbarui kata sandi keamanan.</p>
                    </div>
                </a>
            </div>

            <!-- Account Info -->
            <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
                 <div class="border-b border-slate-100 px-8 py-5 bg-slate-50/50">
                     <h3 class="font-bold text-slate-800 flex items-center gap-2">
                         <i class="bi bi-person-badge text-indigo-500"></i>
                         Informasi Akun
                     </h3>
                 </div>
                 <div class="p-0">
                     <table class="w-full text-left text-sm">
                         <tbody class="divide-y divide-slate-100">
                             <tr class="group hover:bg-slate-50 transition-colors">
                                 <td class="whitespace-nowrap px-8 py-4 font-medium text-slate-500 w-1/3">Nama Lengkap</td>
                                 <td class="whitespace-nowrap px-8 py-4 text-slate-800 font-bold">{{ $user->name }}</td>
                             </tr>
                             <tr class="group hover:bg-slate-50 transition-colors">
                                 <td class="whitespace-nowrap px-8 py-4 font-medium text-slate-500">Username</td>
                                 <td class="whitespace-nowrap px-8 py-4 font-mono text-slate-600 bg-slate-50/50">{{ $user->username }}</td>
                             </tr>
                             <tr class="group hover:bg-slate-50 transition-colors">
                                 <td class="whitespace-nowrap px-8 py-4 font-medium text-slate-500">Role Akses</td>
                                 <td class="whitespace-nowrap px-8 py-4">
                                     <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold bg-slate-100 text-slate-600">
                                         {{ $user->role->label() }}
                                     </span>
                                 </td>
                             </tr>
                             <tr class="group hover:bg-slate-50 transition-colors">
                                 <td class="whitespace-nowrap px-8 py-4 font-medium text-slate-500">Status Akun</td>
                                 <td class="whitespace-nowrap px-8 py-4">
                                     @if($user->is_active)
                                         <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                             <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                             Active
                                         </span>
                                     @else
                                         <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                             <span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>
                                             Inactive
                                         </span>
                                     @endif
                                 </td>
                             </tr>
                         </tbody>
                     </table>
                 </div>
            </div>

            <!-- Danger Zone -->
            <div class="rounded-3xl bg-white ring-1 ring-rose-100 overflow-hidden shadow-sm">
                <div class="border-b border-rose-100 bg-rose-50/50 px-8 py-5">
                    <h3 class="font-bold text-rose-700 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Zona Berbahaya
                    </h3>
                </div>
                <div class="p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">Nonaktifkan Akun</h4>
                            <p class="mt-1 text-sm text-slate-500 leading-relaxed max-w-md">
                                Menonaktifkan akun akan memblokir akses login Anda ke sistem. Akun hanya dapat diaktifkan kembali oleh Administrator lain.
                            </p>
                        </div>
                        
                        @if($user->isAdmin())
                            @php
                                $activeAdminCount = App\Models\User::where('role', 'admin')->where('is_active', true)->count();
                            @endphp
                            @if($activeAdminCount <= 1)
                                <button disabled class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-400 cursor-not-allowed">
                                    Tidak Dapat Dinonaktifkan
                                </button>
                                <p class="text-[10px] text-rose-500 mt-2 sm:hidden">Anda adalah satu-satunya admin aktif.</p>
                            @else
                                <button @click="deactivateModalOpen = true" type="button" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-all">
                                    Nonaktifkan Akun
                                </button>
                            @endif
                        @else
                            <button @click="deactivateModalOpen = true" type="button" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-all">
                                Nonaktifkan Akun
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: System Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-3xl bg-slate-900 p-6 text-white shadow-xl shadow-slate-200 relative overflow-hidden">
                 <div class="absolute top-0 right-0 -mr-8 -mt-8 h-40 w-40 rounded-full bg-white opacity-5 blur-2xl"></div>
                 
                 <div class="relative z-10 text-center">
                     <div class="mx-auto mb-4 h-16 w-16 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                         <i class="bi bi-cpu text-3xl text-indigo-300"></i>
                     </div>
                     <h3 class="text-lg font-bold">SIPENCAT System</h3>
                     <p class="text-slate-400 text-sm mt-1">Version 1.0.0 (Beta)</p>
                     
                     <div class="mt-6 pt-6 border-t border-white/10 text-xs text-slate-400">
                         <p>&copy; {{ date('Y') }} Secure Inventory</p>
                         <p class="mt-1">Built with Laravel & Tailwind</p>
                     </div>
                 </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100/50">
                 <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                     <i class="bi bi-life-preserver text-indigo-500"></i> Bantuan
                 </h4>
                 <ul class="space-y-4 text-sm text-slate-600">
                     <li class="flex items-start gap-3">
                         <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 text-xs">1</div>
                         <span>Hubungi Administrator jika mengalami kendala akses.</span>
                     </li>
                     <li class="flex items-start gap-3">
                         <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 text-xs">2</div>
                         <span>Pastikan koneksi internet stabil saat melakukan transaksi.</span>
                     </li>
                 </ul>
            </div>
        </div>
    </div>

    <!-- Deactivate Modal (Alpine.js) -->
    <div x-show="deactivateModalOpen" 
         style="display: none;" 
         class="relative z-[100]" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="deactivateModalOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Panel -->
                <div x-show="deactivateModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.outside="deactivateModalOpen = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <!-- Header -->
                    <div class="bg-rose-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-white font-bold flex items-center gap-2 text-lg">
                            <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Perubahan
                        </h3>
                        <button @click="deactivateModalOpen = false" type="button" class="text-rose-100 hover:text-white transition-colors">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('profile.deactivate') }}" method="POST">
                        @csrf
                        <div class="p-6 bg-white">
                            <div class="rounded-xl bg-rose-50 border border-rose-100 p-4 mb-4 text-sm text-rose-800">
                                <p class="font-bold mb-1">Apakah Anda yakin ingin menonaktifkan akun?</p>
                                <p>Anda akan logout otomatis dan tidak bisa login kembali tanpa bantuan admin.</p>
                            </div>

                            <div class="space-y-2">
                                <label for="password_deactivate" class="block text-sm font-bold text-slate-700">Masukkan Password Anda</label>
                                <input type="password" 
                                       id="password_deactivate" 
                                       name="password" 
                                       class="block w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-rose-500 focus:ring-rose-200 transition-all font-mono"
                                       placeholder="Ketik password untuk konfirmasi"
                                       required>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                            <button type="submit" class="inline-flex justify-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-rose-200 hover:bg-rose-700 sm:w-auto">
                                Ya, Nonaktifkan
                            </button>
                            <button type="button" @click="deactivateModalOpen = false" class="inline-flex justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-600 border border-slate-200 hover:bg-slate-50 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
