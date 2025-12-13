@extends('layouts.app')

@section('title', 'Informasi Shift Saya')
@section('page-title', 'Shift Saya')
@section('page-subtitle', 'Lihat informasi shift dan jadwal kerja Anda')

@section('content')
<div class="max-w-7xl mx-auto">
    @if(!$hasShift)
        {{-- No Shift Assigned --}}
        <div class="rounded-md bg-yellow-50 p-4 border border-yellow-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Belum Ada Shift</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Shift Information --}}
        
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex items-center space-x-5">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-people-fill text-3xl text-indigo-600"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $shift->name }}</h1>
                        <p class="text-sm font-medium text-gray-500 flex items-center mt-1">
                            <i class="bi bi-info-circle mr-1"></i>
                            {{ $shift->description ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex">
                    @if($shift->is_active)
                        <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            <i class="bi bi-check-circle mr-1.5"></i> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-50 px-3 py-1 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                            <i class="bi bi-x-circle mr-1.5"></i> Nonaktif
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Current Status -->
            <div class="mt-6 border-t border-gray-100 pt-6">
                @php
                    $statusColor = $currentStatus['color'] == 'success' ? 'emerald' : ($currentStatus['color'] == 'warning' ? 'amber' : 'red');
                @endphp
                <div class="rounded-md bg-{{ $statusColor }}-50 p-4 border border-{{ $statusColor }}-100">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-clock-fill text-{{ $statusColor }}-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-{{ $statusColor }}-800">
                                Status Saat Ini: {{ $currentStatus['message'] }}
                            </h3>
                            <p class="mt-1 text-xs text-{{ $statusColor }}-700">
                                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Schedule -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center">
                            <i class="bi bi-calendar-week mr-2 text-indigo-500"></i>
                            Jadwal Jam Kerja
                        </h3>
                    </div>
                    
                    @if($workingHours->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Hari</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jam Mulai</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jam Selesai</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Durasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($workingHours as $wh)
                                        @php
                                            $isToday = strtolower(now()->format('l')) === $wh->day_of_week;
                                            $start = \Carbon\Carbon::parse($wh->start_time);
                                            $end = \Carbon\Carbon::parse($wh->end_time);
                                            $duration = $start->diffInHours($end);
                                        @endphp
                                        <tr class="{{ $isToday ? 'bg-indigo-50/50' : 'hover:bg-gray-50' }} transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $isToday ? 'text-indigo-700' : 'text-gray-900' }}">
                                                <div class="flex items-center">
                                                    @if($isToday)
                                                        <i class="bi bi-calendar-check-fill mr-2 text-indigo-600"></i>
                                                    @endif
                                                    {{ $wh->day_name }}
                                                    @if($isToday)
                                                        <span class="ml-2 inline-flex items-center rounded-md bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700">Hari Ini</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <i class="bi bi-clock mr-2 text-gray-400"></i>
                                                    {{ $start->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <i class="bi bi-clock-fill mr-2 text-gray-400"></i>
                                                    {{ $end->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                                                    {{ $duration }} Jam
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="bi bi-calendar-x text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-gray-500">Belum ada jadwal jam kerja untuk shift ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Members & Stats -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Members Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center justify-between">
                            <span class="flex items-center">
                                <i class="bi bi-people mr-2 text-indigo-500"></i> Anggota Shift
                            </span>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                {{ $members->count() }}
                            </span>
                        </h3>
                    </div>
                    <ul role="list" class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                        @forelse($members as $member)
                            <li class="flex items-center gap-x-4 px-6 py-4 hover:bg-gray-50 transition-colors {{ $member->id === auth()->id() ? 'bg-indigo-50/30' : '' }}">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold border-2 border-white shadow-sm">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-auto">
                                    <p class="text-sm font-semibold leading-6 text-gray-900 flex items-center">
                                        {{ $member->name }}
                                        @if($member->id === auth()->id())
                                            <span class="ml-2 inline-flex items-center rounded-md bg-indigo-50 px-1.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">Anda</span>
                                        @endif
                                    </p>
                                    <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $member->email }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-6 text-center text-sm text-gray-500 italic">
                                Tidak ada anggota
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wider">Statistik Shift</h3>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-4">
                        <div class="border-l-4 border-indigo-500 bg-gray-50 pl-4 py-2 rounded-r-md">
                            <dt class="text-xs font-medium text-gray-500">Total Anggota</dt>
                            <dd class="mt-1 text-xl font-bold tracking-tight text-indigo-600">{{ $members->count() }}</dd>
                        </div>
                        <div class="border-l-4 border-teal-500 bg-gray-50 pl-4 py-2 rounded-r-md">
                            <dt class="text-xs font-medium text-gray-500">Hari Kerja/Minggu</dt>
                            <dd class="mt-1 text-xl font-bold tracking-tight text-teal-600">{{ $workingHours->count() }}</dd>
                        </div>
                        <div class="col-span-2 border-l-4 border-blue-500 bg-gray-50 pl-4 py-2 rounded-r-md">
                            <dt class="text-xs font-medium text-gray-500">Total Jam Kerja / Minggu</dt>
                            <dd class="mt-1 text-xl font-bold tracking-tight text-blue-600">
                                {{ $workingHours->sum(function($wh) {
                                    $start = \Carbon\Carbon::parse($wh->start_time);
                                    $end = \Carbon\Carbon::parse($wh->end_time);
                                    return $start->diffInHours($end);
                                }) }} Jam
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
