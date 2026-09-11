<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\BankAccount;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PaymentForm
{
    /**
     * @param  bool  $withProject  Sembunyikan pemilih proyek saat dipakai di relation manager.
     */
    public static function configure(Schema $schema, bool $withProject = true): Schema
    {
        return $schema->components([
            Section::make('Rincian Pembayaran')
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

                    Select::make('type')
                        ->label('Jenis pembayaran')
                        ->options(PaymentType::class)
                        ->default(PaymentType::DownPayment)
                        ->required()
                        ->native(false),

                    TextInput::make('amount')
                        ->label('Nominal')
                        ->numeric()
                        ->minValue(0)
                        ->step(1000)
                        ->prefix('Rp')
                        ->required()
                        ->helperText('Isi angka saja tanpa titik, misal 1750000.'),

                    Select::make('method')
                        ->label('Metode')
                        ->options(PaymentMethod::class)
                        ->default(PaymentMethod::Transfer)
                        ->required()
                        ->native(false)
                        ->live(),

                    Select::make('bank_account_id')
                        ->label('Bank tujuan')
                        ->options(fn () => BankAccount::query()
                            ->ordered()
                            ->get()
                            ->mapWithKeys(fn (BankAccount $bank) => [$bank->id => $bank->bank_name.' - '.$bank->account_number])
                            ->all())
                        ->native(false)
                        ->visible(fn (Get $get) => $get('method') === PaymentMethod::Transfer->value)
                        ->requiredIf('method', PaymentMethod::Transfer->value),

                    DatePicker::make('paid_at')
                        ->label('Tanggal bayar')
                        ->native(false)
                        ->displayFormat('d F Y')
                        ->default(now())
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options(PaymentStatus::class)
                        ->default(PaymentStatus::Pending)
                        ->required()
                        ->native(false)
                        ->live()
                        ->helperText('Hanya pembayaran terverifikasi yang dihitung sebagai omzet.'),
                ]))),

            Section::make('Bukti & Catatan')
                ->schema([
                    FileUpload::make('proof_path')
                        ->label('Bukti pembayaran')
                        ->disk('private')
                        ->directory('bukti-pembayaran')
                        ->visibility('private')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->maxSize(2048)
                        ->downloadable()
                        ->openable()
                        ->helperText('JPG, PNG, atau PDF maksimal 2MB. Disimpan di penyimpanan privat dan tidak bisa diakses lewat URL publik.'),

                    Textarea::make('note')
                        ->label('Catatan')
                        ->rows(2),

                    Textarea::make('rejection_reason')
                        ->label('Alasan ditolak')
                        ->rows(2)
                        ->visible(fn (Get $get) => $get('status') === PaymentStatus::Rejected->value)
                        ->requiredIf('status', PaymentStatus::Rejected->value)
                        ->helperText('Alasan ini ditampilkan ke klien di halaman invoice.'),
                ]),
        ]);
    }
}
