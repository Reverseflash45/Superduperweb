<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ExpenseCategory: string implements HasLabel
{
    case Domain = 'domain';
    case Hosting = 'hosting';
    case License = 'lisensi';
    case Outsource = 'outsource';
    case Other = 'lainnya';

    public function getLabel(): string
    {
        return match ($this) {
            self::Domain => 'Domain',
            self::Hosting => 'Hosting',
            self::License => 'Lisensi / Plugin',
            self::Outsource => 'Outsource',
            self::Other => 'Lain-lain',
        };
    }
}
