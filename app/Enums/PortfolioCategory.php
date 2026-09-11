<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PortfolioCategory: string implements HasLabel
{
    case Web = 'web';
    case Mobile = 'mobile';
    case CustomSystem = 'sistem-custom';

    public function getLabel(): string
    {
        return match ($this) {
            self::Web => 'Web',
            self::Mobile => 'Mobile',
            self::CustomSystem => 'Sistem Custom',
        };
    }
}
