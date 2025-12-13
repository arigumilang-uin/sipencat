<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    use HasFactory;

    protected $table = 'login_history';
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'success',
        'failure_reason',
        'login_at',
        'logout_at',
        'session_duration',
    ];
    
    protected $casts = [
        'success' => 'boolean',
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    /**
     * Relationship: Login history belongs to user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get browser name from user agent
     */
    public function getBrowserAttribute(): string
    {
        $userAgent = $this->user_agent ?? '';
        
        if (str_contains($userAgent, 'Edge')) {
            return 'Microsoft Edge';
        } elseif (str_contains($userAgent, 'Chrome')) {
            return 'Google Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'Mozilla Firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        }
        
        return 'Unknown Browser';
    }

    /**
     * Get formatted session duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->session_duration) {
            return '-';
        }
        
        $hours = floor($this->session_duration / 60);
        $minutes = $this->session_duration % 60;
        
        if ($hours > 0) {
            return "{$hours} jam {$minutes} menit";
        }
        
        return "{$minutes} menit";
    }
}
