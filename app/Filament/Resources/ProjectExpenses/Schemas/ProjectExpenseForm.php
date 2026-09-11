<?php

namespace App\Filament\Resources\ProjectExpenses\Schemas;

use App\Enums\ExpenseCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectExpenseForm
{
    /**
     * @param  bool  $withProject  Sembunyikan pemilih proyek saat dipakai di relation manager.
     */
    public static function configure(Schema $schema, bool $withProject = true): Schema
    {
        return $schema->components([
            Section::make('Rincian Biaya')
                ->columns(2)
                ->schema(array_values(array_filter([
                    $withProject
                        ? Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false)
                            ->columnSpanFull()
                        : null,

                    Select::make('category')
                        ->label('Kategori')
                        ->options(ExpenseCategory::class)
                        ->default(ExpenseCategory::Other)
                        ->required()
                        ->native(false),

                    TextInput::make('amount')
                        ->label('Nominal')
                        ->numeric()
                        ->minValue(0)
                        ->step(1000)
                        ->prefix('Rp')
                        ->required()
                        ->helperText('Isi angka saja tanpa titik, misal 180000.'),

                    DatePicker::make('expense_date')
                        ->label('Tanggal')
                        ->native(false)
                        ->displayFormat('d F Y')
                        ->default(now())
                        ->required(),

                    TextInput::make('description')
                        ->label('Keterangan')
                        ->maxLength(255)
                        ->placeholder('Domain usahaku.id 1 tahun'),
                ]))),
        ]);
    }
}
