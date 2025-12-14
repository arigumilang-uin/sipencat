@extends('layouts.app')

@section('title', 'Kelola Overtime')
@section('page-title', 'Persetujuan Overtime')
@section('page-subtitle', 'Tinjau dan kelola permintaan perpanjangan waktu kerja staff')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Permintaan Overtime</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola pengajuan lembur staff operasional dengan mudah.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pending -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-amber-100">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-amber-500/10 blur-2xl"></div>
            <div class="relative flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <i class="bi bi-hourglass-split text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Menunggu Persetujuan</p>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $pendingRequests->count() }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-amber-500"></span>
                <span class="text-xs text-amber-600 font-medium">Permintaan baru hari ini</span>
            </div>
        </div>

        <!-- Active/Approved Today -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-emerald-100">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-emerald-500/10 blur-2xl"></div>
            <div class="relative flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Sedang Berjalan</p>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $activeExtensions }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs text-emerald-600 font-medium">Staff sedang lembur sekarang</span>
            </div>
        </div>

        <!-- History Total -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-indigo-100">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y--8 rounded-full bg-indigo-500/10 blur-2xl"></div>
            <div class="relative flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                    <i class="bi bi-clock-history text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Riwayat</p>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $completedRequests->total() }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-500"></span>
                <span class="text-xs text-indigo-600 font-medium">Arsip permintaan selesai</span>
            </div>
        </div>
    </div>

    <!-- Pending Requests List -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="bg-amber-50/50 px-6 py-4 border-b border-amber-100/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-hourglass-split text-amber-500"></i>
                Menunggu Persetujuan
                <span class="inline-flex items-center justify-center rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-bold text-white shadow-sm ring-1 ring-inset ring-amber-600/10">
                    {{ $pendingRequests->count() }} Pending
                </span>
            </h3>
        </div>

        @forelse($pendingRequests as $request)
            <div class="p-6 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors">
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <!-- User Info & Request -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                                {{ substr($request->user->name, 0, 1) }}
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">{{ $request->user->name }}</h4>
                                @if($request->user->shift()->exists())
                                    <p class="text-xs text-slate-500">{{ $request->user->shift->first()->name }}</p>
                                @else
                                    <p class="text-xs text-slate-500">No Shift</p>
                                @endif
                            </div>
                            <span class="text-xs text-slate-400">•</span>
                            <span class="text-xs text-slate-400">{{ $request->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-50/50">
                            <p class="text-sm font-medium text-slate-700 mb-1">
                                <span class="text-indigo-600 font-bold">Alasan:</span> {{ $request->reason }}
                            </p>
                            <p class="text-xs text-slate-500 flex items-center gap-1">
                                <i class="bi bi-clock"></i>
                                Meminta perpanjangan waktu <span class="font-bold text-slate-700">{{ $request->requested_minutes }} menit</span>
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <button type="button" 
                                class="inline-flex flex-1 md:flex-none items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition-all duration-200 hover:bg-emerald-700 hover:shadow-emerald-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                data-bs-toggle="modal" 
                                data-bs-target="#approveModal{{ $request->id }}">
                            <i class="bi bi-check-lg mr-2"></i> Setujui
                        </button>
                        <button type="button" 
                                class="inline-flex flex-1 md:flex-none items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-rose-600 shadow-sm border border-rose-100 transition-all duration-200 hover:bg-rose-50 hover:border-rose-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal{{ $request->id }}">
                            <i class="bi bi-x-lg mr-2"></i> Tolak
                        </button>
                    </div>
                </div>

                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('admin.overtime.approve', $request) }}" method="POST">
                            @csrf
                            <div class="modal-content rounded-2xl border-0 shadow-2xl">
                                <div class="modal-header border-b-0 pb-0">
                                    <h5 class="modal-title font-bold text-slate-900">Setujui Perpanjangan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="rounded-xl bg-indigo-50 p-4 mb-4">
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-info-circle-fill text-indigo-500 mt-0.5"></i>
                                            <div class="text-sm text-indigo-900">
                                                <p><span class="font-bold">{{ $request->user->name }}</span> meminta <span class="font-bold">{{ $request->requested_minutes }} menit</span>.</p>
                                                <p class="text-xs mt-1 text-indigo-700">Anda dapat menyetujui sesuai permintaan atau mengubah durasinya.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Durasi Disetujui (Menit)</label>
                                        <input type="number" name="granted_minutes" class="block w-full rounded-xl border-slate-200 py-2.5 px-4 text-sm focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700" value="{{ $request->requested_minutes }}" min="5" max="240" required>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Catatan Admin (Opsional)</label>
                                        <textarea name="admin_notes" rows="2" class="block w-full rounded-xl border-slate-200 py-2.5 px-4 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Pesan untuk staff..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-t-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-xl font-bold text-slate-600" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success rounded-xl font-bold bg-emerald-600 text-white shadow-lg shadow-emerald-200 border-0">
                                        Setujui Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('admin.overtime.reject', $request) }}" method="POST">
                            @csrf
                            <div class="modal-content rounded-2xl border-0 shadow-2xl">
                                <div class="modal-header border-b-0 pb-0">
                                    <h5 class="modal-title font-bold text-slate-900">Tolak Perpanjangan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="rounded-xl bg-rose-50 p-4 mb-4">
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-exclamation-triangle-fill text-rose-500 mt-0.5"></i>
                                            <div class="text-sm text-rose-900">
                                                <p>Anda akan menolak permintaan dari <span class="font-bold">{{ $request->user->name }}</span>.</p>
                                                <p class="text-xs mt-1 text-rose-700">Wajib memberikan alasan penolakan agar staff mengerti.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                        <textarea name="admin_notes" rows="3" class="block w-full rounded-xl border-slate-200 py-2.5 px-4 text-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-t-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-xl font-bold text-slate-600" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger rounded-xl font-bold bg-rose-600 text-white shadow-lg shadow-rose-200 border-0">
                                        Tolak Permintaan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="rounded-full bg-slate-50 p-4 mb-4 ring-1 ring-slate-100">
                    <i class="bi bi-inbox-fill text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Tidak ada permintaan pending</h3>
                <p class="text-sm text-slate-500 mt-1">Semua permintaan lembur telah diproses.</p>
            </div>
        @endforelse
    </div>

    <!-- History Table -->
    <div class="rounded-3xl bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] ring-1 ring-slate-100/50 overflow-hidden">
        <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-clock-history text-indigo-500"></i>
                Riwayat Permintaan
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-indigo-50/20 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">User</th>
                        <th class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Alasan</th>
                        <th class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-center">Durasi</th>
                        <th class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider">Diproses Oleh</th>
                        <th class="px-6 py-4 font-bold uppercase text-[11px] tracking-wider text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($completedRequests as $request)
                        <tr class="group hover:bg-indigo-50/10 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold">
                                        {{ substr($request->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700">{{ $request->user->name }}</p>
                                        @if($request->user->shift()->exists())
                                            <p class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $request->user->shift->first()->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-slate-600 truncate max-w-xs" title="{{ $request->reason }}">
                                    {{ Str::limit($request->reason, 40) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-bold text-slate-500">Minta: {{ $request->requested_minutes }}m</span>
                                    @if($request->granted_minutes)
                                        <span class="text-xs font-bold text-emerald-600">ACC: {{ $request->granted_minutes }}m</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClass = match($request->status) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        'rejected' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                        default => 'bg-slate-50 text-slate-700 ring-slate-600/20'
                                    };
                                    $statusIcon = match($request->status) {
                                        'approved' => 'bi-check-circle-fill',
                                        'rejected' => 'bi-x-circle-fill',
                                        default => 'bi-hourglass'
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $statusClass }} uppercase tracking-wider">
                                    <i class="bi {{ $statusIcon }} mr-1.5 text-[10px]"></i>
                                    {{ $request->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($request->approver)
                                    <p class="text-sm text-slate-600">{{ $request->approver->name }}</p>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-xs text-slate-500">{{ $request->created_at->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">{{ $request->created_at->format('H:i') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Belum ada riwayat permintaan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
            {{ $completedRequests->links() }}
        </div>
    </div>
</div>
@endsection
