<?php

namespace App\Filament\Resources\ProjectExpenses\Pages;

use App\Filament\Resources\ProjectExpenses\ProjectExpenseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectExpense extends EditRecord
{
    protected static string $resource = ProjectExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
