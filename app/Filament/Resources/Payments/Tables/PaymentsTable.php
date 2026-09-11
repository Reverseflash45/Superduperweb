<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table, bool $withProject = true): Table
    {
        return $table
            ->defaultSort('paid_at', 'desc')
            ->columns(array_values(array_filter([
                TextColumn::make('paid_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                $withProject
                    ? TextColumn::make('project.title')
                        ->label('Proyek')
                        ->searchable()
                        ->wrap()
                        ->description(fn ($record) => $record->project?->client?->name)
                    : null,

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->alignEnd()
                    ->sortable()
                    ->weight('bold')
                    ->formatStateUsing(fn ($state) => rupiah($state)),

                TextColumn::make('method')
                    ->label('Metode')
                    ->badge()
                    ->color('info')
                    ->description(fn ($record) => $record->bankAccount?->bank_name),

                IconColumn::make('proof_path')
                    ->label('Bukti')
                    ->alignCenter()
                    ->boolean()
                    ->getStateUsing(fn ($record) => filled($record->proof_path))
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-minus-small')
                    ->falseColor('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
            ])))
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PaymentStatus::class)
                    ->multiple(),

                SelectFilter::make('type')
                    ->label('Jenis')
                    ->options(PaymentType::class)
                    ->multiple(),

                SelectFilter::make('method')
                    ->label('Metode')
                    ->options(PaymentMethod::class),
            ])
            ->recordActions([
                PaymentActions::downloadProof(),
                PaymentActions::verify(),
                PaymentActions::reject(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pembayaran')
            ->emptyStateDescription('Pembayaran bisa dicatat manual di sini, atau masuk otomatis saat klien mengunggah bukti lewat halaman invoice.');
    }
}
