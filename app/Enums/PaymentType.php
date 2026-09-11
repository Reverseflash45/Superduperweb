<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentType: string implements HasLabel
{
    case DownPayment = 'dp';
    case Instalment = 'termin';
    case Settlement = 'pelunasan';
    case Maintenance = 'maintenance';

    public function getLabel(): string
    {
        return match ($this) {
            self::DownPayment => 'DP',
            self::Instalment => 'Termin',
            self::Settlement => 'Pelunasan',
            self::Maintenance => 'Maintenance',
        };
    }
}
