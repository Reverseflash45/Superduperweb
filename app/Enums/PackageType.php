<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PackageType: string implements HasLabel
{
    case Project = 'project';
    case Maintenance = 'maintenance';

    public function getLabel(): string
    {
        return match ($this) {
            self::Project => 'Paket Proyek',
            self::Maintenance => 'Maintenance Bulanan',
        };
    }
}
