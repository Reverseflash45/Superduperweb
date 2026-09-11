<?php

namespace App\Filament\Resources\Packages\Schemas;

use App\Enums\PackageType;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Paket')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama paket')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Dipakai untuk URL. Otomatis terisi dari nama paket.'),

                        Select::make('type')
                            ->label('Jenis paket')
                            ->options(PackageType::class)
                            ->default(PackageType::Project)
                            ->required()
                            ->native(false)
                            ->helperText('Paket proyek dan maintenance bulanan ditampilkan di tab terpisah pada landing page.'),

                        TextInput::make('tagline')
                            ->label('Tagline singkat')
                            ->maxLength(255)
                            ->placeholder('Satu halaman fokus jualan, cepat tayang.'),
                    ]),

                Section::make('Harga & Ketentuan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->minValue(0)
                            ->step(1000)
                            ->prefix('Rp')
                            ->helperText('Isi angka saja tanpa titik, misal 1500000. Kosongkan bila ingin menampilkan "Hubungi kami".'),

                        Toggle::make('is_starting_price')
                            ->label('Tampilkan sebagai "mulai dari"')
                            ->helperText('Aktifkan untuk paket yang harganya tergantung kebutuhan.')
                            ->inline(false),

                        TextInput::make('duration_estimate')
                            ->label('Estimasi waktu pengerjaan')
                            ->maxLength(255)
                            ->placeholder('5-7 hari kerja'),

                        TextInput::make('revision_count')
                            ->label('Jumlah revisi')
                            ->maxLength(255)
                            ->placeholder('2x revisi'),

                        TextInput::make('demo_url')
                            ->label('Link demo (opsional)')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link')
                            ->columnSpanFull(),
                    ]),

                Section::make('Daftar Fitur')
                    ->schema([
                        Repeater::make('features')
                            ->label('Fitur')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('feature')
                                    ->placeholder('Domain .com gratis 1 tahun')
                                    ->required(),
                            )
                            ->addActionLabel('Tambah fitur')
                            ->reorderableWithDragAndDrop()
                            ->defaultItems(3),
                    ]),

                Section::make('Tampilan')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_popular')
                            ->label('Badge "Paling Populer"')
                            ->inline(false),

                        Toggle::make('is_active')
                            ->label('Tampilkan di situs')
                            ->default(true)
                            ->inline(false),

                        TextInput::make('sort_order')
                            ->label('Urutan tampil')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka kecil tampil lebih dulu.'),
                    ]),
            ]);
    }
}
