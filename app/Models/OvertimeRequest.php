<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OvertimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'reason',
        'requested_minutes',
        'granted_minutes',
        'status',
        'expires_at',
        'admin_notes',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relationship: Request belongs to user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Approved by admin
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if extension is still valid
     */
    public function isActive(): bool
    {
        return $this->status === 'approved' 
            && $this->expires_at 
            && now()->lessThan($this->expires_at);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Scope: Pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Active extensions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->where('expires_at', '>', now());
    }
}
