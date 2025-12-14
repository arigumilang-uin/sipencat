@extends('layouts.app')

@section('title', 'Kelola Shift')
@section('page-title', 'Kelola Shift')
@section('page-subtitle', 'Manajemen shift dan anggota staff operasional')

@section('content')
<div class="space-y-8">
    <!-- Header & Info -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola Shift</h1>
            <p class="mt-1 text-sm text-slate-500">Buat dan atur pembagian shift untuk Staff Operasional.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5 flex gap-4 items-start">
        <div class="flex-shrink-0">
            <i class="bi bi-info-circle-fill text-xl text-indigo-500"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-indigo-900 mb-1">Informasi Shift</h4>
            <p class="text-sm text-indigo-700 leading-relaxed">
                Setiap Staff Operasional hanya dapat terdaftar dalam satu Shift pada satu waktu. 
                Shift digunakan untuk mengelompokkan jadwal kerja dan memudahkan manajemen absensi.
            </p>
        </div>
    </div>

    <!-- Create Form -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6 sm:p-8">
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
            <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="bi bi-plus-lg text-sm font-bold"></i>
            </div>
            Buat Shift Baru
        </h3>

        <form action="{{ route('admin.shifts.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 md:grid-cols-12 items-end">
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nama Shift <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('name') border-rose-300 bg-rose-50 text-rose-900 @enderror" placeholder="Contoh: Shift Pagi" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Deskripsi</label>
                    <input type="text" name="description" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200" placeholder="Contoh: Operasional pagi 08:00 - 17:00" value="{{ old('description') }}">
                </div>

                <div class="md:col-span-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="bi bi-save mr-2"></i>
                        Simpan Shift
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Available Staff List -->
    @if($availableUsers->count() > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">
            <h4 class="text-sm font-bold text-amber-800 mb-3 flex items-center">
                <i class="bi bi-person-plus-fill mr-2 text-lg"></i>
                Staff Operasional Belum Memiliki Shift ({{ $availableUsers->count() }})
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach($availableUsers as $user)
                    <span class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-amber-700 shadow-sm border border-amber-100">
                        <div class="h-5 w-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mr-2 text-[10px] font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        {{ $user->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Shift List -->
    @forelse($shifts as $shift)
        <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $shift->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $shift->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <span class="ml-2 inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">
                        {{ $shift->members_count }} Staff
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.shifts.toggle', $shift) }}" method="POST">
                         @csrf
                         <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200 {{ $shift->is_active ? 'text-amber-600 bg-amber-50 hover:bg-amber-100' : 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100' }}">
                             <i class="bi bi-toggle-{{ $shift->is_active ? 'on' : 'off' }} mr-2 text-lg"></i>
                             {{ $shift->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                         </button>
                    </form>
                    
                    <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" onsubmit="return confirm('Yakin hapus shift {{ $shift->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-rose-600 bg-rose-50 rounded-lg hover:bg-rose-100 transition-all duration-200">
                            <i class="bi bi-trash mr-2"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Add Member & List -->
            <div class="p-6">
                <!-- Add Member Form -->
                <div class="mb-6 bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <form action="{{ route('admin.shifts.add-member', $shift) }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                        @csrf
                        <div class="w-full sm:flex-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Tambah Anggota ke {{ $shift->name }}</label>
                            <select name="user_id" class="block w-full rounded-lg border-slate-200 bg-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200" required>
                                <option value="">Pilih Staff Operasional...</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-md hover:bg-indigo-500 transition-all duration-200">
                            <i class="bi bi-person-plus-fill mr-2"></i>
                            Tambahkan
                        </button>
                    </form>
                </div>

                <!-- Member Table -->
                @if($shift->members->count() > 0)
                    <div class="overflow-hidden rounded-xl border border-slate-100">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-indigo-50/30">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-indigo-500 uppercase tracking-wider">Nama Staff</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-indigo-500 uppercase tracking-wider">Username</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-indigo-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-indigo-500 uppercase tracking-wider">Aksi</th>
                                </a>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($shift->members as $member)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-bold text-slate-700">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                                {{ $member->name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-mono text-slate-500">{{ $member->username }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-500">{{ $member->email ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('admin.shifts.remove-member', [$shift, $member]) }}" method="POST" onsubmit="return confirm('Keluarkan {{ $member->name }} dari shift ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:underline transition-colors">
                                                    Keluarkan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                        <i class="bi bi-people text-slate-300 text-2xl mb-2 block"></i>
                        <span class="text-sm text-slate-500">Belum ada anggota di shift ini.</span>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="rounded-3xl bg-white p-12 text-center shadow-sm border border-slate-100">
            <div class="mx-auto h-24 w-24 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                <i class="bi bi-layers text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Belum Ada Shift</h3>
            <p class="text-slate-500 mt-2 max-w-sm mx-auto">Silakan buat shift baru untuk mulai mengatur staff operasional.</p>
        </div>
    @endforelse
</div>
@endsection
