<?php

namespace App\Filament\Resources\Portfolios\Tables;

use App\Enums\PortfolioCategory;
use App\Enums\PortfolioType;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PortfoliosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->imageHeight(48)
                    ->imageWidth(76),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->client_name),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('project_type')
                    ->label('Jenis')
                    ->badge()
                    ->sortable(),

                TextColumn::make('tech_stack')
                    ->label('Tech stack')
                    ->badge()
                    ->color('info')
                    ->limitList(3)
                    ->toggleable(),

                TextColumn::make('images_count')
                    ->label('Galeri')
                    ->counts('images')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => $state.' gambar'),

                TextColumn::make('completed_at')
                    ->label('Selesai')
                    ->date('M Y')
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('is_published')
                    ->label('Tampil'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(PortfolioCategory::class),

                SelectFilter::make('project_type')
                    ->label('Jenis proyek')
                    ->options(PortfolioType::class),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn ($record) => route('portfolio.show', $record->slug))
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada portofolio')
            ->emptyStateDescription('Tambahkan proyek yang sudah selesai agar calon klien bisa melihat hasil kerja kamu.');
    }
}
