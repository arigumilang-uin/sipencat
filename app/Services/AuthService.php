<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Maximum login attempts before lockout
     */
    private const MAX_ATTEMPTS = 5;

    /**
     * Lockout duration in seconds (5 minutes)
     */
    private const DECAY_SECONDS = 300;

    /**
     * Attempt to authenticate user with credentials
     *
     * @param array $credentials
     * @param bool $remember
     * @return User
     * @throws ValidationException
     */
    public function login(array $credentials, bool $remember = false): User
    {
        $username = $credentials['username'];
        $password = $credentials['password'];

        // Check rate limiting
        $this->ensureIsNotRateLimited($username);

        // Find user by username
        $user = User::where('username', $username)->first();

        // Validate credentials
        if (!$user || !Hash::check($password, $user->password)) {
            $this->hitRateLimit($username);
            
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }


        // Check if user is active
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'username' => ['Akun Anda tidak aktif. Hubungi administrator.'],
            ]);
        }

        // Check working hours for role-based access (Time-based Access Control)
        if (!\App\Models\WorkingHour::canAccessNow($user->role->value)) {
            $nextAccessTime = \App\Models\WorkingHour::getNextAccessTime($user->role->value);
            $dayName = now()->locale('id')->dayName;
            
            throw ValidationException::withMessages([
                'username' => [
                    "Akses ditolak. Anda hanya dapat login pada jam kerja yang ditentukan." . 
                    ($nextAccessTime ? " Jam kerja hari {$dayName} dimulai pukul {$nextAccessTime}." : "")
                ],
            ]);
        }


        // Clear rate limiter on successful login
        RateLimiter::clear($this->throttleKey($username));

        // Perform authentication
        Auth::login($user, $remember);

        // Regenerate session to prevent session fixation
        request()->session()->regenerate();

        // Track last login (Security monitoring)
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);

        // Log login history untuk audit trail
        \App\Models\LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'success' => true,
            'login_at' => now(),
        ]);

        return $user;
    }

    /**
     * Logout the current user
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @param string $username
     * @return void
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(string $username): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($username), self::MAX_ATTEMPTS)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($username));
        $minutes = ceil($seconds / 60);

        throw ValidationException::withMessages([
            'username' => ["Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit."],
        ]);
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param string $username
     * @return void
     */
    protected function hitRateLimit(string $username): void
    {
        RateLimiter::hit($this->throttleKey($username), self::DECAY_SECONDS);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @param string $username
     * @return string
     */
    protected function throttleKey(string $username): string
    {
        return strtolower($username) . '|' . request()->ip();
    }

    /**
     * Get remaining login attempts
     *
     * @param string $username
     * @return int
     */
    public function getRemainingAttempts(string $username): int
    {
        $key = $this->throttleKey($username);
        $attempts = RateLimiter::attempts($key);
        
        return max(0, self::MAX_ATTEMPTS - $attempts);
    }
}
