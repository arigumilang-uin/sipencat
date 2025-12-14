@extends('layouts.app')

@section('title', 'Kelola Jam Kerja')
@section('page-title', 'Kelola Jam Kerja')
@section('page-subtitle', 'Atur jam kerja untuk shift staff operasional')

@section('content')
<div class="space-y-8">
    <!-- Header & Info -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola Jam Kerja</h1>
            <p class="mt-1 text-sm text-slate-500">Konfigurasi jadwal kerja dan batasan login untuk Staff Operasional berdasarkan Shift.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5 flex gap-4 items-start">
        <div class="flex-shrink-0">
            <i class="bi bi-info-circle-fill text-xl text-indigo-500"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-indigo-900 mb-1">Informasi Akses</h4>
            <p class="text-sm text-indigo-700 leading-relaxed">
                Jam kerja digunakan untuk membatasi akses login Staff Operasional. 
                <strong>Admin</strong> dan <strong>Pemilik</strong> memiliki akses penuh 24/7 tanpa batasan shift.
                Jika jadwal shift tidak diatur untuk hari tertentu, akses akan terbuka penuh pada hari tersebut.
            </p>
        </div>
    </div>

    <!-- Create Form -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 p-6 sm:p-8">
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
            <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="bi bi-clock-history text-sm"></i>
            </div>
            Tambah Jadwal Shift
        </h3>

        <form action="{{ route('admin.working-hours.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2 lg:grid-cols-12 items-end">
                <!-- Shift Select -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Shift <span class="text-rose-500">*</span></label>
                    <select name="shift_id" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('shift_id') border-rose-300 bg-rose-50 text-rose-900 @enderror" required>
                        <option value="">Pilih Shift</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name }} ({{ $shift->members_count }} anggota)
                            </option>
                        @endforeach
                    </select>
                    @error('shift_id')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Day Select -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Hari <span class="text-rose-500">*</span></label>
                    <select name="day_of_week" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('day_of_week') border-rose-300 bg-rose-50 text-rose-900 @enderror" required>
                        <option value="">Pilih Hari</option>
                        @foreach($days as $dayValue => $dayName)
                            <option value="{{ $dayValue }}" {{ old('day_of_week') == $dayValue ? 'selected' : '' }}>
                                {{ $dayName }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div class="sm:col-span-1 lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Mulai <span class="text-rose-500">*</span></label>
                    <input type="time" name="start_time" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('start_time') border-rose-300 bg-rose-50 text-rose-900 @enderror" value="{{ old('start_time', '08:00') }}" required>
                </div>

                <!-- End Time -->
                <div class="sm:col-span-1 lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Selesai <span class="text-rose-500">*</span></label>
                    <input type="time" name="end_time" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-4 text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 @error('end_time') border-rose-300 bg-rose-50 text-rose-900 @enderror" value="{{ old('end_time', '17:00') }}" required>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all duration-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="bi bi-plus-lg mr-2 font-bold"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Shift-Based Working Hours List -->
    @if($workingHoursByShift->count() > 0)
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 ml-1">
                <i class="bi bi-people-fill text-indigo-500"></i>
                Daftar Jadwal Per Shift
            </h3>
            
            @foreach($workingHoursByShift as $shiftId => $hours)
                @php
                    $shift = $hours->first()->shift;
                @endphp
                <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] overflow-hidden ring-1 ring-slate-100/50">
                    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <h4 class="text-base font-bold text-slate-800">{{ $shift->name }}</h4>
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                {{ $shift->members_count }} Anggota
                            </span>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-indigo-50/20 text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider w-16">No</th>
                                    <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Hari</th>
                                    <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Mulai</th>
                                    <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Selesai</th>
                                    <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Durasi</th>
                                    <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($hours as $index => $wh)
                                    <tr class="group hover:bg-indigo-50/10 transition-colors duration-200">
                                        <td class="px-6 py-4 text-slate-400 font-medium">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-bold text-slate-700">{{ $wh->day_name }}</td>
                                        <td class="px-6 py-4 font-mono text-slate-600">{{ \Carbon\Carbon::parse($wh->start_time)->format('H:i') }}</td>
                                        <td class="px-6 py-4 font-mono text-slate-600">{{ \Carbon\Carbon::parse($wh->end_time)->format('H:i') }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $start = \Carbon\Carbon::parse($wh->start_time);
                                                $end = \Carbon\Carbon::parse($wh->end_time);
                                                $duration = $start->diffInHours($end);
                                            @endphp
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                                {{ $duration }} Jam
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($wh->is_active)
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <form action="{{ route('admin.working-hours.toggle', $wh) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="group/btn flex items-center justify-center h-8 w-8 rounded-full {{ $wh->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-500' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-500' }} hover:text-white transition-all duration-200 shadow-sm" title="{{ $wh->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="bi bi-toggle-{{ $wh->is_active ? 'off' : 'on' }} text-sm"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.working-hours.destroy', $wh) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="group/btn flex items-center justify-center h-8 w-8 rounded-full bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm" title="Hapus">
                                                        <i class="bi bi-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-3xl bg-white p-12 text-center shadow-sm border border-slate-100">
            <div class="mx-auto h-24 w-24 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                <i class="bi bi-clock text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Belum Ada Jadwal Shift</h3>
            <p class="text-slate-500 mt-2 max-w-sm mx-auto">Tambahkan jadwal jam kerja berdasarkan shift menggunakan formulir di atas.</p>
        </div>
    @endif
</div>
@endsection
