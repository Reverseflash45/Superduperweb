<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: string implements HasColor, HasLabel
{
    case Queued = 'antrian';
    case InProgress = 'pengerjaan';
    case Revision = 'revisi';
    case AwaitingSettlement = 'menunggu-pelunasan';
    case Completed = 'selesai';
    case Cancelled = 'batal';

    public function getLabel(): string
    {
        return match ($this) {
            self::Queued => 'Antrian',
            self::InProgress => 'Pengerjaan',
            self::Revision => 'Revisi',
            self::AwaitingSettlement => 'Menunggu Pelunasan',
            self::Completed => 'Selesai',
            self::Cancelled => 'Batal',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Queued => 'gray',
            self::InProgress => 'info',
            self::Revision => 'warning',
            self::AwaitingSettlement => 'primary',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }

    /** Status yang dianggap sebagai proyek aktif (sedang berjalan). */
    public static function activeStatuses(): array
    {
        return [self::Queued, self::InProgress, self::Revision, self::AwaitingSettlement];
    }
}
