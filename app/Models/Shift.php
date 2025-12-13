<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Shift has many users (members)
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shift_members')
            ->withTimestamps();
    }

    /**
     * Relationship: Shift has many working hours rules
     */
    public function workingHours(): HasMany
    {
        return $this->hasMany(WorkingHour::class);
    }

    /**
     * Get active members only
     */
    public function activeMembers(): BelongsToMany
    {
        return $this->members()->where('is_active', true);
    }

    /**
     * Get member count
     */
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    /**
     * Check if shift has specific user
     */
    public function hasMember(int $userId): bool
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * Add user to shift
     */
    public function addMember(int $userId): bool
    {
        if ($this->hasMember($userId)) {
            return false;
        }

        $this->members()->attach($userId);
        return true;
    }

    /**
     * Remove user from shift
     */
    public function removeMember(int $userId): bool
    {
        return $this->members()->detach($userId) > 0;
    }
}
