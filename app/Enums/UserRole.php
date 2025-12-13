<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case STAFF_OPERASIONAL = 'staff_operasional';
    case PEMILIK = 'pemilik';

    /**
     * Get all role values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get role label for display
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::STAFF_OPERASIONAL => 'Staff Operasional',
            self::PEMILIK => 'Pemilik',
        };
    }

    /**
     * Check if role is admin
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Check if role is staff operasional
     */
    public function isStaffOperasional(): bool
    {
        return $this === self::STAFF_OPERASIONAL;
    }

    /**
     * Backward compatibility: alias for isStaffOperasional
     */
    public function isGudang(): bool
    {
        return $this->isStaffOperasional();
    }

    /**
     * Check if role is pemilik
     */
    public function isPemilik(): bool
    {
        return $this === self::PEMILIK;
    }
}
