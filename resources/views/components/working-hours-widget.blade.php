@php
    $workingHours = getUserWorkingHoursToday();
@endphp

@if($workingHours && $workingHours['has_restriction'] && isset($workingHours['type']) && $workingHours['type'] === 'blocked')
    {{-- Blocked State: No working hours defined --}}
    <div class="rounded-lg bg-white shadow-sm border border-rose-200 overflow-hidden">
        <div class="border-b border-rose-200 px-4 py-4 bg-rose-600 text-white">
            <h5 class="flex items-center text-base font-semibold">
                <i class="bi bi-slash-circle mr-2"></i>
                Status Jam Kerja
            </h5>
        </div>
        <div class="p-5">
            <div class="rounded-md bg-rose-50 p-4 border border-rose-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-triangle-fill text-rose-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-rose-800">{{ $workingHours['message'] }}</h3>
                        <div class="mt-2 text-sm text-rose-700">
                            <p>
                                Hubungi Administrator untuk mengatur jadwal jam kerja Anda. 
                                Anda tidak dapat mengakses sistem tanpa jadwal yang ditentukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($workingHours && $workingHours['has_restriction'])
    <div class="rounded-lg bg-white shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <h5 class="flex items-center text-base font-semibold">
                <i class="bi bi-clock-history mr-2"></i>
                Informasi Jam Kerja Anda
            </h5>
        </div>
        <div class="p-5 space-y-4">
            {{-- Extension Mode Badge --}}
            @if(isset($workingHours['is_extension']) && $workingHours['is_extension'])
                <div class="rounded-md bg-amber-50 border border-amber-200 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-hourglass-split text-amber-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800">Mode Perpanjangan Waktu</h3>
                            <div class="mt-1 text-sm text-amber-700">
                                <p>Anda mendapat tambahan {{ $workingHours['granted_minutes'] }} menit waktu akses.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(isset($workingHours['shift_name']) && $workingHours['shift_name'])
                <div class="flex items-center justify-between text-sm">
                    <strong class="text-gray-600 flex items-center"><i class="bi bi-people mr-2"></i> Shift:</strong> 
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                        {{ $workingHours['shift_name'] }}
                    </span>
                </div>
            @endif
            
            <div class="flex items-center justify-between text-sm">
                @if(isset($workingHours['is_extension']) && $workingHours['is_extension'])
                    <strong class="text-gray-600 flex items-center"><i class="bi bi-hourglass-top mr-2"></i> Waktu Ekstensi:</strong>
                @else
                    <strong class="text-gray-600 flex items-center"><i class="bi bi-calendar-check mr-2"></i> Jam Kerja:</strong>
                @endif
                <span class="text-lg font-bold text-indigo-600 font-mono tracking-tight">
                    {{ \Carbon\Carbon::parse($workingHours['start_time'])->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($workingHours['end_time'])->format('H:i') }}
                </span>
            </div>
            
            @if($workingHours['is_active_now'])
                @php
                    $remaining = calculateRemainingWorkTime($workingHours['end_time']);
                    $percentage = getWorkTimePercentage($workingHours['start_time'], $workingHours['end_time']);
                @endphp
                
                @if(!$remaining['expired'])
                    <div class="rounded-lg bg-emerald-50 border border-emerald-100 p-4" id="countdown-container">
                        <div class="flex justify-between items-center mb-3">
                            <h6 class="text-sm font-semibold text-emerald-900 flex items-center">
                                <i class="bi bi-hourglass-split mr-2"></i>
                                <span id="countdown-label">Sisa Waktu Kerja</span>
                            </h6>
                            @if($remaining['total_minutes'] <= 30)
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20 animate-pulse">
                                    <i class="bi bi-exclamation-triangle mr-1"></i> Segera Berakhir
                                </span>
                            @endif
                        </div>
                        
                        <!-- Countdown Timer -->
                        <div class="countdown-timer text-center mb-4" 
                             data-end-time="{{ \Carbon\Carbon::parse($workingHours['end_time'])->timestamp }}">
                            <h2 class="text-3xl font-bold text-gray-900 font-mono tracking-wider" id="countdown-display">
                                {{ sprintf('%02d:%02d:%02d', $remaining['hours'], $remaining['minutes'], $remaining['seconds']) }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Jam : Menit : Detik</p>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200">
                                        Progress
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-emerald-600">
                                        <span id="progress-percentage">{{ number_format($percentage, 1) }}</span>%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-emerald-200">
                                <div id="work-progress-bar"
                                     style="width: {{ $percentage }}%" 
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-emerald-500 to-emerald-400 transition-all duration-1000"></div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center text-xs text-gray-500 mt-2 border-t border-emerald-200/50 pt-2">
                            <span>
                                <i class="bi bi-box-arrow-in-right mr-1"></i>
                                Masuk: <strong>{{ \Carbon\Carbon::parse($workingHours['start_time'])->format('H:i') }}</strong>
                            </span>
                            <span>
                                <i class="bi bi-box-arrow-right mr-1"></i>
                                Keluar: <strong>{{ \Carbon\Carbon::parse($workingHours['end_time'])->format('H:i') }}</strong>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock-fill text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Jam Kerja Telah Berakhir</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Waktu kerja Anda berakhir pada pukul {{ \Carbon\Carbon::parse($workingHours['end_time'])->format('H:i') }}.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-md bg-amber-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-exclamation-triangle text-amber-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800">Di Luar Jam Kerja</h3>
                            <div class="mt-2 text-sm text-amber-700">
                                <p>Jam kerja dimulai pukul {{ \Carbon\Carbon::parse($workingHours['start_time'])->format('H:i') }} WIB.</p>
                            </div>
                        </div>
                    </div>
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
                container.classList.remove('bg-emerald-50', 'border-emerald-100', 'bg-amber-50', 'border-amber-100');
                container.classList.add('bg-red-50', 'border-red-100');
                
                const labelText = container.querySelector('h6');
                if(labelText) labelText.className = "text-sm font-semibold text-red-900 flex items-center";
                label.innerHTML = '<i class="bi bi-clock-fill mr-2"></i> Jam Kerja Berakhir';
                
                progressBar.classList.remove('from-emerald-500', 'to-emerald-400');
                progressBar.classList.add('bg-red-600'); 
                progressBar.style.width = '100%';
                
                progressText.textContent = '100.0';
                
                // Check if redirect message already exists
                if (!document.getElementById('redirect-message')) {
                    // Show redirecting message (only once)
                    const redirectMessage = document.createElement('div');
                    redirectMessage.id = 'redirect-message';
                    redirectMessage.className = 'mt-4 rounded-md bg-blue-50 p-3';
                    redirectMessage.innerHTML = '<div class="flex"><div class="flex-shrink-0"><i class="bi bi-arrow-right-circle text-blue-400"></i></div><div class="ml-3 flex-1 md:flex md:justify-between"><p class="text-sm text-blue-700">Mengalihkan ke halaman akhir kerja dalam <span id="redirect-countdown" class="font-bold">3</span> detik...</p></div></div>';
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
                container.classList.remove('bg-emerald-50', 'border-emerald-100', 'bg-amber-50', 'border-amber-100');
                container.classList.add('bg-red-50', 'border-red-100');
                const labelText = container.querySelector('h6');
                if(labelText) labelText.className = "text-sm font-semibold text-red-900 flex items-center";
                
                label.innerHTML = '<i class="bi bi-exclamation-circle mr-2"></i> SEGERA BERAKHIR!';
                
                progressBar.classList.remove('from-emerald-500', 'to-emerald-400', 'from-amber-500', 'to-amber-400');
                progressBar.classList.add('bg-gradient-to-r', 'from-red-500', 'to-red-600');
                
            } else if (remaining <= 1800) { // < 30 minutes  
                container.classList.remove('bg-emerald-50', 'border-emerald-100', 'bg-red-50', 'border-red-100');
                container.classList.add('bg-amber-50', 'border-amber-100');
                const labelText = container.querySelector('h6');
                if(labelText) labelText.className = "text-sm font-semibold text-amber-900 flex items-center";

                label.innerHTML = '<i class="bi bi-exclamation-triangle mr-2"></i> Segera Berakhir';
                
                progressBar.classList.remove('from-emerald-500', 'to-emerald-400');
                progressBar.classList.add('bg-gradient-to-r', 'from-amber-500', 'to-amber-400');
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
    <div class="rounded-lg bg-white shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-4 bg-emerald-600 text-white">
            <h5 class="flex items-center text-base font-semibold">
                <i class="bi bi-check-circle mr-2"></i>
                Status Jam Kerja
            </h5>
        </div>
        <div class="p-5">
            <div class="rounded-md bg-emerald-50 p-4 border border-emerald-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-infinity text-emerald-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-emerald-800">{{ $workingHours['message'] }}</h3>
                        <div class="mt-2 text-sm text-emerald-700">
                            <p>
                                @if(auth()->user()->role->value === 'admin')
                                    Sebagai Administrator, Anda memiliki akses penuh ke sistem kapan saja.
                                @else
                                    Tidak ada pembatasan jam kerja untuk Anda hari ini.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
