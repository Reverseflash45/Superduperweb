<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PortfolioType: string implements HasColor, HasLabel
{
    case Client = 'klien';
    case Personal = 'pribadi';
    case Demo = 'demo';

    public function getLabel(): string
    {
        return match ($this) {
            self::Client => 'Proyek Klien',
            self::Personal => 'Proyek Pribadi',
            self::Demo => 'Demo',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Client => 'success',
            self::Personal => 'info',
            self::Demo => 'warning',
        };
    }
}
