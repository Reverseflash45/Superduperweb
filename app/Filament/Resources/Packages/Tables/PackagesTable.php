<?php

namespace App\Filament\Resources\Packages\Tables;

use App\Enums\PackageType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama paket')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->tagline),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->alignEnd()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->price_label),

                TextColumn::make('duration_estimate')
                    ->label('Estimasi')
                    ->toggleable(),

                TextColumn::make('revision_count')
                    ->label('Revisi')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('features')
                    ->label('Fitur')
                    ->alignCenter()
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state).' fitur' : '-')
                    ->state(fn ($record) => $record->features),

                IconColumn::make('is_popular')
                    ->label('Populer')
                    ->boolean()
                    ->alignCenter(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis paket')
                    ->options(PackageType::class),

                TernaryFilter::make('is_active')
                    ->label('Status tampil')
                    ->trueLabel('Ditampilkan')
                    ->falseLabel('Disembunyikan')
                    ->placeholder('Semua'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada paket')
            ->emptyStateDescription('Tambahkan paket agar section Paket & Harga muncul di landing page.');
    }
}
