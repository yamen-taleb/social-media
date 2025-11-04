<?php

namespace App\Enums;

enum UserApprovalEnum: string
{
    case APPROVED = 'approved';
    case PENDING = 'pending';

    const REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Approved',
            self::PENDING => 'Pending',
            self::REJECTED => 'Rejected',
        };
    }

    public function classes(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-50 text-yellow-800 inset-ring-yellow-600/20 dark:bg-yellow-400/10 dark:text-yellow-500 dark:inset-ring-yellow-400/20',
            self::APPROVED => '',
        };
    }
}
