<?php

namespace App\Filament\Resources\Projects;

use App\Enums\PaymentStatus;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\RelationManagers\ExpensesRelationManager;
use App\Filament\Resources\Projects\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Proyek';

    protected static ?string $pluralModelLabel = 'Proyek';

    protected static ?string $recordTitleAttribute = 'title';

    /**
     * Total dibayar dan total biaya dihitung lewat subquery agar tabel proyek
     * tidak menembak query terpisah untuk setiap baris.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('client')
            ->withSum(
                ['payments as verified_payments_sum' => fn (Builder $query) => $query->where('status', PaymentStatus::Verified)],
                'amount',
            )
            ->withSum('expenses as expenses_sum', 'amount');
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
            ExpensesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
