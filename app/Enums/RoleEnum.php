<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case MEMBER = 'member';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::MEMBER => 'Member',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-red-50 text-red-700 inset-ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:inset-ring-red-400/20',
            self::MEMBER => 'bg-blue-50 text-blue-700 inset-ring-blue-600/10 dark:bg-blue-400/10 dark:text-blue-400 dark:inset-ring-blue-400/20',
        };
    }
}
