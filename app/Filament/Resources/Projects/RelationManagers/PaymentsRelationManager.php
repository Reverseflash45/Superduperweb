<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\PaymentStatus;
use App\Filament\Resources\Payments\Schemas\PaymentForm;
use App\Filament\Resources\Payments\Tables\PaymentsTable;
use App\Models\Project;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    /** Ringkasan tagihan ditampilkan sebagai badge di judul tab. */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        /** @var Project $ownerRecord */
        $pending = $ownerRecord->payments()->where('status', PaymentStatus::Pending)->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getBadgeColor(Model $ownerRecord, string $pageClass): ?string
    {
        return 'warning';
    }

    public function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema, withProject: false);
    }

    public function table(Table $table): Table
    {
        /** @var Project $project */
        $project = $this->getOwnerRecord();

        return PaymentsTable::configure($table, withProject: false)
            ->heading('Pembayaran')
            ->description(sprintf(
                'Harga deal %s · Terverifikasi %s · Sisa tagihan %s',
                rupiah($project->deal_price),
                rupiah($project->total_paid),
                rupiah($project->remaining),
            ))
            ->headerActions([
                CreateAction::make()
                    ->label('Catat pembayaran')
                    ->mutateDataUsing(function (array $data): array {
                        if (($data['status'] ?? null) === PaymentStatus::Verified->value) {
                            $data['verified_at'] = now();
                        }

                        return $data;
                    }),
            ]);
    }
}
