<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Proyek')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap()
                    ->description(fn (Project $record) => $record->client?->display_name),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('deal_price')
                    ->label('Harga deal')
                    ->alignEnd()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => rupiah($state)),

                TextColumn::make('verified_payments_sum')
                    ->label('Dibayar')
                    ->alignEnd()
                    ->sortable()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => rupiah($state))
                    ->description('terverifikasi'),

                TextColumn::make('sisa_tagihan')
                    ->label('Sisa tagihan')
                    ->alignEnd()
                    ->state(fn (Project $record) => max(0, $record->deal_price - (int) $record->verified_payments_sum))
                    ->formatStateUsing(fn ($state) => rupiah($state))
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray')
                    ->weight(fn ($state) => $state > 0 ? 'bold' : null),

                TextColumn::make('expenses_sum')
                    ->label('Biaya')
                    ->alignEnd()
                    ->sortable()
                    ->color('danger')
                    ->formatStateUsing(fn ($state) => rupiah($state))
                    ->toggleable(),

                TextColumn::make('margin')
                    ->label('Margin')
                    ->alignEnd()
                    ->state(fn (Project $record) => (int) $record->verified_payments_sum - (int) $record->expenses_sum)
                    ->formatStateUsing(fn ($state) => rupiah($state))
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->weight('bold'),

                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-')
                    ->color(fn (Project $record) => $record->deadline
                        && $record->deadline->isPast()
                        && ! in_array($record->status, [ProjectStatus::Completed, ProjectStatus::Cancelled], true)
                            ? 'danger'
                            : null)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ProjectStatus::class)
                    ->multiple(),

                SelectFilter::make('client')
                    ->label('Klien')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('belum_lunas')
                    ->label('Belum lunas')
                    ->query(fn (Builder $query) => $query->whereRaw(
                        'deal_price > (select coalesce(sum(amount), 0) from payments where payments.project_id = projects.id and payments.status = ?)',
                        ['terverifikasi'],
                    )),

                Filter::make('lewat_deadline')
                    ->label('Lewat deadline')
                    ->query(fn (Builder $query) => $query
                        ->whereDate('deadline', '<', now())
                        ->whereNotIn('status', [ProjectStatus::Completed->value, ProjectStatus::Cancelled->value])),
            ])
            ->recordActions([
                Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->url(fn (Project $record) => route('invoice.show', $record->invoice_code))
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada proyek')
            ->emptyStateDescription('Buat proyek manual, atau pakai tombol "Jadikan Proyek" pada brief yang sudah deal.');
    }
}
