@extends('layouts.app')

@section('title', 'Jam Kerja Berakhir')
@section('page-title', 'Terima Kasih')
@section('page-subtitle', 'Sampai jumpa besok!')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Thank You Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Icon -->
                    <div class="mb-4">
                        <i class="bi bi-clock-history text-primary" style="font-size: 5rem;"></i>
                    </div>
                    
                    <!-- Main Message -->
                    <h2 class="fw-bold mb-3">Jam Kerja Anda Telah Berakhir</h2>
                    <p class="lead text-muted mb-4">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </p>
                    
                    <!-- Motivational Messages (Random) -->
                    @php
                        $messages = [
                            [
                                'title' => 'Terima Kasih Atas Kerja Keras Anda!',
                                'message' => 'Dedikasi dan usaha Anda hari ini sangat berarti bagi kemajuan tim. Istirahat yang cukup adalah bagian penting dari produktivitas.',
                                'icon' => 'emoji-smile'
                            ],
                            [
                                'title' => 'Pekerjaan Hebat Hari Ini!',
                                'message' => 'Setiap tugas yang Anda selesaikan hari ini membantu mencapai tujuan bersama. Saatnya beristirahat dan pulih untuk hari esok yang lebih baik!',
                                'icon' => 'trophy'
                            ],
                            [
                                'title' => 'Anda Telah Melakukan Yang Terbaik!',
                                'message' => 'Konsistensi Anda dalam mengelola inventory sangat dihargai. Nikmati waktu istirahat Anda dengan baik!',
                                'icon' => 'heart'
                            ],
                            [
                                'title' => 'Kerja Bagus, Waktunya Istirahat!',
                                'message' => 'Produktivitas yang baik dimulai dari istirahat yang cukup. Terima kasih telah menjaga sistem tetap berjalan lancar!',
                                'icon' => 'star-fill'
                            ],
                        ];
                        $selected = $messages[array_rand($messages)];
                    @endphp
                    
                    <div class="alert alert-success bg-success bg-opacity-10 border-success mb-4">
                        <h4 class="alert-heading">
                            <i class="bi bi-{{ $selected['icon'] }} me-2"></i>
                            {{ $selected['title'] }}
                        </h4>
                        <p class="mb-0">{{ $selected['message'] }}</p>
                    </div>
                    
                    <!-- Extension Request Section -->
                    <div class="border-top pt-4 mt-4">
                        <h5 class="mb-3">
                            <i class="bi bi-hourglass-split me-2"></i>
                            Pekerjaan Belum Selesai?
                        </h5>
                        <p class="text-muted mb-3">
                            Jika Anda masih memiliki tugas mendesak yang perlu diselesaikan, 
                            Anda dapat mengajukan perpanjangan waktu akses kepada Administrator.
                        </p>
                        
                        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#extensionModal">
                            <i class="bi bi-send me-2"></i>
                            Ajukan Perpanjangan Waktu
                        </button>
                    </div>
                    
                    <!-- Logout Info -->
                    <div class="mt-4">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Anda akan otomatis logout dalam <span id="auto-logout-countdown">10</span> detik...
                        </p>
                        <div class="mt-2">
                            <a href="{{ route('logout') }}" class="btn btn-outline-secondary btn-sm" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Logout Sekarang
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats (Optional) -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check text-success fs-3"></i>
                            <h6 class="mt-2 mb-0">Jam Kerja Besok</h6>
                            <small class="text-muted">{{ \Carbon\Carbon::tomorrow()->locale('id')->dayName }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-moon-stars text-primary fs-3"></i>
                            <h6 class="mt-2 mb-0">Istirahat yang Cukup</h6>
                            <small class="text-muted">Energi untuk esok hari</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extension Request Modal -->
<div class="modal fade" id="extensionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('overtime.request') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Ajukan Perpanjangan Waktu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Permintaan Anda akan dikirim ke Administrator untuk persetujuan.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Alasan Perpanjangan Waktu <span class="text-danger">*</span>
                        </label>
                        <textarea name="reason" class="form-control" rows="3" required 
                                  placeholder="Contoh: Masih ada 5 transaksi barang masuk yang perlu diinput..."></textarea>
                        <small class="text-muted">Jelaskan mengapa Anda memerlukan waktu tambahan</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Durasi yang Dibutuhkan</label>
                        <select name="requested_minutes" class="form-select" required>
                            <option value="15">15 Menit</option>
                            <option value="30" selected>30 Menit</option>
                            <option value="60">1 Jam</option>
                            <option value="120">2 Jam</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-logout countdown
let countdown = 10;
const countdownElement = document.getElementById('auto-logout-countdown');
const logoutForm = document.getElementById('logout-form');

const timer = setInterval(() => {
    countdown--;
    countdownElement.textContent = countdown;
    
    if (countdown <= 0) {
        clearInterval(timer);
        logoutForm.submit();
    }
}, 1000);

// Cancel auto-logout if user opens modal
document.getElementById('extensionModal')?.addEventListener('show.bs.modal', () => {
    clearInterval(timer);
    countdownElement.textContent = 'dibatalkan';
});
</script>
@endpush
@endsection
