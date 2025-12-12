<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case GUDANG = 'gudang';
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
            self::GUDANG => 'Staff Gudang',
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
     * Check if role is gudang
     */
    public function isGudang(): bool
    {
        return $this === self::GUDANG;
    }

    /**
     * Check if role is pemilik
     */
    public function isPemilik(): bool
    {
        return $this === self::PEMILIK;
    }
}
