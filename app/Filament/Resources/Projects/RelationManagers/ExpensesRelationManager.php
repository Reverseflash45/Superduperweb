<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\ExpenseCategory;
use App\Filament\Resources\ProjectExpenses\Schemas\ProjectExpenseForm;
use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    protected static ?string $title = 'Biaya';

    protected static ?string $modelLabel = 'Biaya';

    public function form(Schema $schema): Schema
    {
        return ProjectExpenseForm::configure($schema, withProject: false);
    }

    public function table(Table $table): Table
    {
        /** @var Project $project */
        $project = $this->getOwnerRecord();

        return $table
            ->heading('Biaya proyek')
            ->description(sprintf(
                'Total biaya %s · Margin bersih %s',
                rupiah($project->total_expense),
                rupiah($project->margin),
            ))
            ->defaultSort('expense_date', 'desc')
            ->columns([
                TextColumn::make('expense_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),

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
            ])
            ->headerActions([
                CreateAction::make()->label('Catat biaya'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada biaya')
            ->emptyStateDescription('Catat domain, hosting, lisensi, dan outsource supaya margin proyek ini akurat.');
    }
}
