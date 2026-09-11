<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Package;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Proyek')
                    ->columns(2)
                    ->schema([
                        Select::make('client_id')
                            ->label('Klien')
                            ->relationship('client', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Client $record) => $record->display_name)
                            ->searchable(['name', 'business_name', 'whatsapp'])
                            ->preload()
                            ->required()
                            ->native(false)
                            ->createOptionForm([
                                TextInput::make('name')->label('Nama')->required()->maxLength(255),
                                TextInput::make('business_name')->label('Nama usaha')->maxLength(255),
                                TextInput::make('whatsapp')->label('WhatsApp')->required()->tel()->maxLength(30),
                                TextInput::make('email')->label('Email')->email()->maxLength(255),
                            ]),

                        Select::make('package_id')
                            ->label('Paket')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $package = $state ? Package::find($state) : null;

                                if ($package?->price && blank($get('deal_price'))) {
                                    $set('deal_price', $package->price);
                                }
                            })
                            ->helperText('Opsional, untuk proyek di luar paket standar boleh dikosongkan.'),

                        TextInput::make('title')
                            ->label('Judul proyek')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Nilai & Jadwal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('deal_price')
                            ->label('Harga deal')
                            ->numeric()
                            ->minValue(0)
                            ->step(1000)
                            ->prefix('Rp')
                            ->required()
                            ->live(onBlur: true)
                            ->helperText('Isi angka saja tanpa titik, misal 3500000.'),

                        TextInput::make('dp_percentage')
                            ->label('Persentase DP')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(50)
                            ->required()
                            ->live(onBlur: true)
                            ->helperText(fn (Get $get) => 'Nilai DP: '.rupiah((int) round(((int) $get('deal_price')) * ((int) $get('dp_percentage')) / 100))),

                        DatePicker::make('start_date')
                            ->label('Tanggal mulai')
                            ->native(false)
                            ->displayFormat('d F Y'),

                        DatePicker::make('deadline')
                            ->label('Deadline')
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->afterOrEqual('start_date'),

                        Select::make('status')
                            ->label('Status')
                            ->options(ProjectStatus::class)
                            ->default(ProjectStatus::Queued)
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Invoice Klien')
                    ->description('Kode invoice digenerate otomatis dan tidak bisa ditebak. Bagikan link ini ke klien agar mereka bisa melihat tagihan dan mengunggah bukti pembayaran tanpa perlu login.')
                    ->schema([
                        TextEntry::make('invoice_code')
                            ->label('Link invoice')
                            ->state(fn (?Project $record) => $record ? route('invoice.show', $record->invoice_code) : 'Dibuat otomatis setelah proyek disimpan.')
                            ->copyable()
                            ->copyMessage('Link invoice disalin')
                            ->url(fn (?Project $record) => $record ? route('invoice.show', $record->invoice_code) : null, shouldOpenInNewTab: true),
                    ])
                    ->visibleOn('edit'),

                Section::make('Catatan')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan internal')
                            ->rows(4)
                            ->helperText('Ruang lingkup pekerjaan, kesepakatan khusus, atau hal yang perlu diingat.'),
                    ]),
            ]);
    }
}
