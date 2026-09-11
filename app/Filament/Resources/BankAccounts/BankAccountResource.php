<?php

namespace App\Filament\Resources\BankAccounts;

use App\Filament\Resources\BankAccounts\Pages\CreateBankAccount;
use App\Filament\Resources\BankAccounts\Pages\EditBankAccount;
use App\Filament\Resources\BankAccounts\Pages\ListBankAccounts;
use App\Models\BankAccount;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Rekening Bank';

    protected static ?string $pluralModelLabel = 'Rekening Bank';

    protected static ?string $recordTitleAttribute = 'bank_name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Rekening')
                ->description('Nomor rekening tidak pernah ditampilkan di landing page. Hanya logo bank yang muncul di section Metode Pembayaran; nomor lengkap hanya tampil di halaman invoice klien.')
                ->columns(2)
                ->schema([
                    TextInput::make('bank_name')
                        ->label('Nama bank')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('BCA'),

                    TextInput::make('account_number')
                        ->label('Nomor rekening')
                        ->required()
                        ->maxLength(50)
                        ->placeholder('1234567890'),

                    TextInput::make('account_holder')
                        ->label('Atas nama')
                        ->required()
                        ->maxLength(255),

                    FileUpload::make('logo')
                        ->label('Logo bank')
                        ->image()
                        ->disk('public')
                        ->directory('bank')
                        ->maxSize(1024)
                        ->helperText('Opsional. Kalau kosong, nama bank ditampilkan sebagai teks.'),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true)
                        ->inline(false)
                        ->helperText('Rekening nonaktif tidak muncul di situs maupun di pilihan metode pembayaran klien.'),

                    TextInput::make('sort_order')
                        ->label('Urutan tampil')
                        ->numeric()
                        ->default(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->imageHeight(32)
                    ->imageWidth(84),

                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('account_number')
                    ->label('Nomor rekening')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor rekening disalin'),

                TextColumn::make('account_holder')
                    ->label('Atas nama')
                    ->searchable(),

                TextColumn::make('payments_count')
                    ->label('Dipakai')
                    ->counts('payments')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => $state.'x'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada rekening bank')
            ->emptyStateDescription('Tambahkan rekening agar klien bisa memilih transfer bank di halaman invoice.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBankAccounts::route('/'),
            'create' => CreateBankAccount::route('/create'),
            'edit' => EditBankAccount::route('/{record}/edit'),
        ];
    }
}
