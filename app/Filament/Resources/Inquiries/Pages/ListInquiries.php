<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Enums\InquiryStatus;
use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListInquiries extends ListRecords
{
    protected static string $resource = InquiryResource::class;

    public function getTabs(): array
    {
        $tabs = [
            'semua' => Tab::make('Semua'),
        ];

        foreach (InquiryStatus::cases() as $status) {
            $tabs[$status->value] = Tab::make($status->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status))
                ->badge(InquiryResource::getModel()::query()->where('status', $status)->count())
                ->badgeColor($status->getColor());
        }

        return $tabs;
    }
}
