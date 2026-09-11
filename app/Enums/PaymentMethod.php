<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Qris = 'qris';
    case Transfer = 'transfer';

    public function getLabel(): string
    {
        return match ($this) {
            self::Qris => 'QRIS',
            self::Transfer => 'Transfer Bank',
        };
    }
}
