@extends('layouts.app')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')
@section('page-subtitle', 'Amankan akun Anda dengan password baru')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
            <div class="border-b border-slate-100 px-8 py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-sm">
                        <i class="bi bi-key-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Form Ganti Password</h3>
                        <p class="text-xs text-slate-500">Isi form di bawah untuk mengubah kata sandi.</p>
                    </div>
                </div>
                
                <a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-all duration-200">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Batal
                </a>
            </div>

            <div class="p-8">
                <div class="rounded-xl bg-amber-50 border border-amber-100 p-4 mb-8 flex gap-3">
                     <div class="shrink-0">
                         <i class="bi bi-exclamation-triangle-fill text-amber-500 text-xl"></i>
                     </div>
                     <div>
                         <h5 class="text-sm font-bold text-amber-800">Perhatian Penting</h5>
                         <p class="text-sm text-amber-700 mt-1 leading-relaxed">
                             Setelah berhasil mengubah password, sesi login Anda akan berakhir otomatis. Silakan login kembali menggunakan password baru Anda.
                         </p>
                     </div>
                </div>

                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Old Password -->
                        <div>
                            <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Password Lama <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('current_password') border-rose-300 bg-rose-50 text-rose-900 @enderror" 
                                   required>
                            @error('current_password')
                                <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="h-px bg-slate-100 my-2"></div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('password') border-rose-300 bg-rose-50 text-rose-900 @enderror" 
                                   required>
                            @error('password')
                                <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-[10px] text-slate-400">Minimal 8 karakter.</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                Konfirmasi Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-200 transition-all duration-200" 
                                   required>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-amber-200 transition-all duration-200 hover:bg-amber-600 hover:shadow-amber-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                <i class="bi bi-check-lg mr-2"></i>
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Sidebar Tip -->
    <div class="lg:col-span-1">
        <div class="rounded-3xl bg-indigo-600 p-6 text-white shadow-lg shadow-indigo-200 relative overflow-hidden">
             <div class="absolute top-0 right-0 -mr-8 -mt-8 h-32 w-32 rounded-full bg-white opacity-10 blur-xl"></div>
             
             <div class="relative z-10">
                 <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-4">
                     <i class="bi bi-shield-check text-2xl"></i>
                 </div>
                 
                 <h4 class="text-lg font-bold mb-2">Tips Keamanan</h4>
                 <ul class="space-y-3 text-sm text-indigo-100">
                     <li class="flex items-start gap-2">
                         <i class="bi bi-check-circle-fill text-emerald-300 mt-0.5 shrink-0"></i>
                         <span>Gunakan kombinasi huruf besar, kecil, dan angka.</span>
                     </li>
                     <li class="flex items-start gap-2">
                         <i class="bi bi-check-circle-fill text-emerald-300 mt-0.5 shrink-0"></i>
                         <span>Hindari menggunakan tanggal lahir atau nama panggilan.</span>
                     </li>
                     <li class="flex items-start gap-2">
                         <i class="bi bi-check-circle-fill text-emerald-300 mt-0.5 shrink-0"></i>
                         <span>Ganti password secara berkala (misal: 3 bulan sekali).</span>
                     </li>
                 </ul>
             </div>
        </div>
    </div>
</div>
@endsection
