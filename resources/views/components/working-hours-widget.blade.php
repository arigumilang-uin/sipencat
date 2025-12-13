@php
    $workingHours = getUserWorkingHoursToday();
@endphp

@if($workingHours && $workingHours['has_restriction'])
    <div class="card border-primary shadow-sm">
        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>
                Informasi Jam Kerja Anda
            </h5>
        </div>
        <div class="card-body">
            {{-- Extension Mode Badge --}}
            @if(isset($workingHours['is_extension']) && $workingHours['is_extension'])
                <div class="alert alert-warning border-warning mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hourglass-split fs-4 me-2"></i>
                        <div>
                            <strong>Mode Perpanjangan Waktu</strong>
                            <p class="mb-0 small">Anda mendapat tambahan {{ $workingHours['granted_minutes'] }} menit waktu akses</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(isset($workingHours['shift_name']) && $workingHours['shift_name'])
                <p class="mb-2">
                    <strong><i class="bi bi-people me-1"></i> Shift:</strong> 
                    <span class="badge bg-info ms-1">{{ $workingHours['shift_name'] }}</span>
                </p>
            @endif
            
            <p class="mb-3">
                @if(isset($workingHours['is_extension']) && $workingHours['is_extension'])
                    <strong><i class="bi bi-hourglass-top me-1"></i> Waktu Perpanjangan:</strong><br>
                @else
                    <strong><i class="bi bi-calendar-check me-1"></i> Jam Kerja Hari Ini:</strong><br>
                @endif
                <span class="fs-5 text-primary">
                    <i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($workingHours['start_time'])->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($workingHours['end_time'])->format('H:i') }}
                </span>
            </p>
            
            @if($workingHours['is_active_now'])
                @php
                    $remaining = calculateRemainingWorkTime($workingHours['end_time']);
                    $percentage = getWorkTimePercentage($workingHours['start_time'], $workingHours['end_time']);
                @endphp
                
                @if(!$remaining['expired'])
                    <div class="alert alert-success border-0 shadow-sm mb-0" id="countdown-container">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">
                                <i class="bi bi-hourglass-split me-1"></i>
                                <span id="countdown-label">Sisa Waktu Kerja</span>
                            </h6>
                            @if($remaining['total_minutes'] <= 30)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-exclamation-triangle"></i> Segera Berakhir
                                </span>
                            @endif
                        </div>
                        
                        <!-- Countdown Timer -->
                        <div class="countdown-timer text-center mb-3" 
                             data-end-time="{{ \Carbon\Carbon::parse($workingHours['end_time'])->timestamp }}">
                            <h2 class="mb-0 fw-bold text-dark" id="countdown-display" style="font-family: 'Courier New', monospace;">
                                {{ sprintf('%02d:%02d:%02d', $remaining['hours'], $remaining['minutes'], $remaining['seconds']) }}
                            </h2>
                            <small class="text-muted">Jam : Menit : Detik</small>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Progress</small>
                                <small class="text-muted"><span id="progress-percentage">{{ number_format($percentage, 1) }}</span>%</small>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     id="work-progress-bar"
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%; background: linear-gradient(90deg, #56ab2f 0%, #a8e063 100%);"
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Masuk: {{ \Carbon\Carbon::parse($workingHours['start_time'])->format('H:i') }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Keluar: {{ \Carbon\Carbon::parse($workingHours['end_time'])->format('H:i') }}
                            </small>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-clock-fill me-2"></i>
                        <strong>Jam Kerja Telah Berakhir</strong>
                        <p class="mb-0 mt-2 small">
                            Waktu kerja Anda berakhir pada pukul {{ \Carbon\Carbon::parse($workingHours['end_time'])->format('H:i') }}.
                        </p>
                    </div>
                @endif
            @else
                <div class="alert alert-warning border-0 shadow-sm mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Di Luar Jam Kerja</strong>
                    <p class="mb-0 mt-2 small">
                        Jam kerja dimulai pukul {{ \Carbon\Carbon::parse($workingHours['start_time'])->format('H:i') }} WIB.
                    </p>
                </div>
            @endif
        </div>
    </div>
    
    @push('scripts')
    <script>
    // Live Countdown Timer
    (function() {
        const countdownElement = document.querySelector('.countdown-timer');
        if (!countdownElement) return;
        
        const endTime = parseInt(countdownElement.dataset.endTime);
        const display = document.getElementById('countdown-display');
        const container = document.getElementById('countdown-container');
        const label = document.getElementById('countdown-label');
        const progressBar = document.getElementById('work-progress-bar');
        const progressText = document.getElementById('progress-percentage');
        
        let hasRedirected = false; // Flag to prevent duplicate logic
        
        function updateCountdown() {
            const now = Math.floor(Date.now() / 1000);
            const remaining = endTime - now;
            
            if (remaining <= 0 && !hasRedirected) {
                hasRedirected = true; // Set flag immediately
                
                // Stop main countdown
                clearInterval(interval);
                
                // Time's up!
                display.textContent = '00:00:00';
                container.classList.remove('alert-success', 'alert-warning');
                container.classList.add('alert-danger');
                label.innerHTML = '<i class="bi bi-clock-fill me-1"></i> Jam Kerja Berakhir';
                progressBar.style.width = '100%';
                progressBar.style.background = 'linear-gradient(90deg, #eb3349 0%, #f45c43 100%)';
                progressText.textContent = '100.0';
                
                // Check if redirect message already exists
                if (!document.getElementById('redirect-message')) {
                    // Show redirecting message (only once)
                    const redirectMessage = document.createElement('div');
                    redirectMessage.id = 'redirect-message';
                    redirectMessage.className = 'alert alert-info mt-2 mb-0';
                    redirectMessage.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i> Mengalihkan ke halaman akhir kerja dalam <span id="redirect-countdown">3</span> detik...';
                    container.appendChild(redirectMessage);
                    
                    // Countdown before redirect
                    let redirectCount = 3;
                    const redirectTimer = setInterval(() => {
                        redirectCount--;
                        const countElement = document.getElementById('redirect-countdown');
                        if (countElement) {
                            countElement.textContent = redirectCount;
                        }
                        
                        if (redirectCount <= 0) {
                            clearInterval(redirectTimer);
                            // Redirect to work-ended page
                            window.location.href = '{{ route("work.ended") }}';
                        }
                    }, 1000);
                }
                
                return;
            }
            
            const hours = Math.floor(remaining / 3600);
            const minutes = Math.floor((remaining % 3600) / 60);
            const seconds = remaining % 60;
            
            display.textContent = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
            
            // Color transitions based on remaining time
            if (remaining <= 300) { // < 5 minutes
                container.classList.remove('alert-success', 'alert-warning');
                container.classList.add('alert-danger');
                label.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> SEGERA BERAKHIR!';
                progressBar.style.background = 'linear-gradient(90deg, #eb3349 0%, #f45c43 100%)';
            } else if (remaining <= 1800) { // < 30 minutes  
                container.classList.remove('alert-success', 'alert-danger');
                container.classList.add('alert-warning');
                label.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Segera Berakhir';
                progressBar.style.background = 'linear-gradient(90deg, #f7971e 0%, #ffd200 100%)';
            }
        }
        
        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', () => clearInterval(interval));
    })();
    </script>
    @endpush
@elseif($workingHours && !$workingHours['has_restriction'])
    <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-check-circle me-2"></i>
                Status Jam Kerja
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-success mb-0">
                <i class="bi bi-infinity me-2"></i>
                <strong>{{ $workingHours['message'] }}</strong>
                <p class="mb-0 mt-2 small">
                    @if(auth()->user()->role->value === 'admin')
                        Sebagai Administrator, Anda memiliki akses penuh ke sistem kapan saja.
                    @else
                        Tidak ada pembatasan jam kerja untuk Anda hari ini.
                    @endif
                </p>
            </div>
        </div>
    </div>
@endif
