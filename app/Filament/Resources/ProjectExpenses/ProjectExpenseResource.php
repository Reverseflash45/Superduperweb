<?php

namespace App\Filament\Resources\ProjectExpenses;

use App\Enums\ExpenseCategory;
use App\Filament\Resources\ProjectExpenses\Pages\CreateProjectExpense;
use App\Filament\Resources\ProjectExpenses\Pages\EditProjectExpense;
use App\Filament\Resources\ProjectExpenses\Pages\ListProjectExpenses;
use App\Models\ProjectExpense;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProjectExpenseResource extends Resource
{
    protected static ?string $model = ProjectExpense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Biaya Proyek';

    protected static ?string $pluralModelLabel = 'Biaya Proyek';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('project.client');
    }

    public static function form(Schema $schema): Schema
    {
        return Schemas\ProjectExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('expense_date', 'desc')
            ->columns([
                TextColumn::make('expense_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('project.title')
                    ->label('Proyek')
                    ->searchable()
                    ->wrap()
                    ->description(fn ($record) => $record->project?->client?->name),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap()
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->alignEnd()
                    ->sortable()
                    ->weight('bold')
                    ->color('danger')
                    ->formatStateUsing(fn ($state) => rupiah($state))
                    ->summarize(
                        Sum::make()
                            ->label('Total')
                            ->formatStateUsing(fn ($state) => rupiah($state)),
                    ),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(ExpenseCategory::class)
                    ->multiple(),

                SelectFilter::make('project')
                    ->label('Proyek')
                    ->relationship('project', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada biaya tercatat')
            ->emptyStateDescription('Catat biaya domain, hosting, lisensi, dan outsource agar margin per proyek terhitung akurat.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectExpenses::route('/'),
            'create' => CreateProjectExpense::route('/create'),
            'edit' => EditProjectExpense::route('/{record}/edit'),
        ];
    }
}
