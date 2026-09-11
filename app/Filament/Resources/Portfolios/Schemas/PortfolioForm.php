<?php

namespace App\Filament\Resources\Portfolios\Schemas;

use App\Enums\PortfolioCategory;
use App\Enums\PortfolioType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PortfolioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Proyek')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul proyek')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL halaman detail: /portofolio/slug-ini'),

                        Select::make('category')
                            ->label('Kategori')
                            ->options(PortfolioCategory::class)
                            ->default(PortfolioCategory::Web)
                            ->required()
                            ->native(false)
                            ->helperText('Dipakai untuk filter di halaman /portofolio.'),

                        Select::make('project_type')
                            ->label('Jenis proyek')
                            ->options(PortfolioType::class)
                            ->default(PortfolioType::Client)
                            ->required()
                            ->native(false)
                            ->live(),

                        TextInput::make('client_name')
                            ->label('Nama klien')
                            ->maxLength(255)
                            ->placeholder('Kopi Kenangan Senja, Yogyakarta')
                            ->visible(fn (callable $get) => $get('project_type') === PortfolioType::Client->value)
                            ->helperText('Pastikan klien sudah mengizinkan namanya ditampilkan.'),

                        DatePicker::make('completed_at')
                            ->label('Tanggal selesai')
                            ->native(false)
                            ->displayFormat('d F Y'),
                    ]),

                Section::make('Gambar')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->disk('public')
                            ->directory('portofolio')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Rasio 16:10 paling pas. Maksimal 2MB.'),

                        Repeater::make('images')
                            ->label('Galeri gambar')
                            ->relationship()
                            ->orderColumn('sort_order')
                            ->reorderableWithDragAndDrop()
                            ->collapsible()
                            ->addActionLabel('Tambah gambar')
                            ->itemLabel(fn (array $state): ?string => $state['caption'] ?? 'Gambar')
                            ->columns(2)
                            ->schema([
                                FileUpload::make('path')
                                    ->label('Gambar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('portofolio')
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->required(),

                                TextInput::make('caption')
                                    ->label('Keterangan')
                                    ->maxLength(255)
                                    ->placeholder('Tampilan halaman beranda'),
                            ]),
                    ]),

                Section::make('Cerita Proyek')
                    ->schema([
                        Textarea::make('summary')
                            ->label('Ringkasan')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Satu-dua kalimat yang muncul di kartu portofolio.'),

                        Textarea::make('problem')
                            ->label('Masalah klien')
                            ->rows(4)
                            ->helperText('Kondisi sebelum dikerjakan. Ceritakan masalah nyatanya.'),

                        Textarea::make('solution')
                            ->label('Solusi yang dikerjakan')
                            ->rows(4)
                            ->helperText('Apa yang dibangun dan bagaimana itu menjawab masalah di atas.'),
                    ]),

                Section::make('Detail Teknis')
                    ->columns(2)
                    ->schema([
                        TagsInput::make('features')
                            ->label('Fitur')
                            ->placeholder('Tekan Enter setiap selesai satu fitur')
                            ->columnSpanFull(),

                        TagsInput::make('tech_stack')
                            ->label('Tech stack')
                            ->placeholder('Laravel, Tailwind CSS, MySQL')
                            ->suggestions(['Laravel', 'PHP', 'Livewire', 'Filament', 'Tailwind CSS', 'Alpine.js', 'JavaScript', 'TypeScript', 'React', 'React Native', 'Next.js', 'Go', 'Flutter', 'Dart', 'MySQL', 'PostgreSQL', 'Redis', 'Figma'])
                            ->columnSpanFull(),

                        TextInput::make('demo_url')
                            ->label('Link demo (opsional)')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                    ]),

                Section::make('Tampilan')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
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
