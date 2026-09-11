<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InquiryStatus: string implements HasColor, HasLabel
{
    case New = 'baru';
    case Contacted = 'dihubungi';
    case Deal = 'deal';
    case Cancelled = 'batal';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Contacted => 'Dihubungi',
            self::Deal => 'Deal',
            self::Cancelled => 'Batal',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'warning',
            self::Contacted => 'info',
            self::Deal => 'success',
            self::Cancelled => 'danger',
        };
    }
}
