<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Models\Client;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Klien';

    protected static ?string $pluralModelLabel = 'Klien';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Klien')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('business_name')
                        ->label('Nama usaha / organisasi')
                        ->maxLength(255),

                    TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->required()
                        ->tel()
                        ->maxLength(30)
                        ->placeholder('081234567890'),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Textarea::make('address')
                        ->label('Alamat')
                        ->rows(3)
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Misalnya kebiasaan komunikasi, preferensi jam dihubungi, atau hal penting lain.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->business_name),

                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor disalin'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('-'),

                TextColumn::make('projects_count')
                    ->label('Proyek')
                    ->counts('projects')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Client $record) => wa_link("Halo {$record->name}, saya dari SuperDuperWeb.", $record->whatsapp))
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada klien')
            ->emptyStateDescription('Klien bisa ditambahkan manual, atau dibuat otomatis lewat tombol "Jadikan Proyek" pada brief yang sudah deal.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }
}
