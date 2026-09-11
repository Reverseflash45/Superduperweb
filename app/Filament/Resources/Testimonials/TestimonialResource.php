<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Resources\Testimonials\Pages\EditTestimonial;
use App\Filament\Resources\Testimonials\Pages\ListTestimonials;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Situs';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Testimoni';

    protected static ?string $pluralModelLabel = 'Testimoni';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pemberi Testimoni')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('business_name')
                        ->label('Nama usaha / organisasi')
                        ->maxLength(255),

                    TextInput::make('role')
                        ->label('Jabatan')
                        ->maxLength(255)
                        ->placeholder('Pemilik'),

                    FileUpload::make('avatar')
                        ->label('Foto')
                        ->image()
                        ->avatar()
                        ->disk('public')
                        ->directory('testimoni')
                        ->imageEditor()
                        ->maxSize(1024)
                        ->helperText('Opsional. Kalau kosong, ditampilkan inisial nama.'),
                ]),

            Section::make('Isi Testimoni')
                ->columns(2)
                ->schema([
                    Textarea::make('content')
                        ->label('Testimoni')
                        ->required()
                        ->rows(5)
                        ->maxLength(1000)
                        ->columnSpanFull()
                        ->helperText('Tulis apa adanya sesuai kalimat klien. Testimoni yang spesifik lebih meyakinkan.'),

                    Select::make('rating')
                        ->label('Rating')
                        ->options([5 => '5 bintang', 4 => '4 bintang', 3 => '3 bintang', 2 => '2 bintang', 1 => '1 bintang'])
                        ->default(5)
                        ->required()
                        ->native(false),

                    TextInput::make('sort_order')
                        ->label('Urutan tampil')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_published')
                        ->label('Tampilkan di situs')
                        ->default(true)
                        ->inline(false)
                        ->helperText('Section testimoni otomatis hilang dari landing page kalau tidak ada testimoni yang ditampilkan.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->imageHeight(40)
                    ->imageWidth(40),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => trim(($record->role ? $record->role.' · ' : '').$record->business_name, ' ·') ?: null),

                TextColumn::make('content')
                    ->label('Testimoni')
                    ->limit(70)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->alignCenter()
                    ->formatStateUsing(fn (int $state) => str_repeat('★', $state).str_repeat('☆', 5 - $state))
                    ->color('warning'),

                ToggleColumn::make('is_published')
                    ->label('Tampil'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada testimoni')
            ->emptyStateDescription('Selama belum ada testimoni, section testimoni otomatis disembunyikan dari landing page.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestimonials::route('/'),
            'create' => CreateTestimonial::route('/create'),
            'edit' => EditTestimonial::route('/{record}/edit'),
        ];
    }
}
