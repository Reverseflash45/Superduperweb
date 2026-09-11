<?php

namespace App\Filament\Resources\Inquiries;

use App\Enums\InquiryStatus;
use App\Filament\Resources\Inquiries\Pages\EditInquiry;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Filament\Resources\Inquiries\Tables\InquiriesTable;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Brief Masuk';

    protected static ?string $pluralModelLabel = 'Brief Masuk';

    protected static ?string $recordTitleAttribute = 'name';

    /** Badge jumlah brief yang belum ditindaklanjuti. */
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', InquiryStatus::New)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Isi Brief')
                ->description('Data ini dikirim oleh calon klien lewat halaman /konsultasi dan tidak perlu diubah.')
                ->columns(2)
                ->schema([
                    TextEntry::make('name')->label('Nama'),
                    TextEntry::make('business_name')->label('Nama usaha')->placeholder('-'),
                    TextEntry::make('whatsapp')->label('WhatsApp')->copyable(),
                    TextEntry::make('email')->label('Email')->placeholder('-')->copyable(),
                    TextEntry::make('service_type')->label('Jenis layanan')->badge(),
                    TextEntry::make('budget_range')->label('Kisaran budget')->placeholder('-'),
                    TextEntry::make('deadline')->label('Target selesai')->placeholder('-'),
                    TextEntry::make('created_at')->label('Masuk pada')->dateTime('d M Y, H:i'),
                    TextEntry::make('description')
                        ->label('Deskripsi kebutuhan')
                        ->columnSpanFull()
                        ->prose(),
                ]),

            Section::make('Tindak Lanjut')
                ->columns(2)
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options(InquiryStatus::class)
                        ->required()
                        ->native(false)
                        ->helperText('Tombol "Jadikan Proyek" muncul setelah status diubah menjadi Deal.'),

                    TextInput::make('ip_address')
                        ->label('IP pengirim')
                        ->disabled()
                        ->dehydrated(false),

                    Textarea::make('admin_notes')
                        ->label('Catatan internal')
                        ->rows(4)
                        ->columnSpanFull()
                        ->helperText('Hanya terlihat oleh admin. Misal hasil pembicaraan atau alasan dibatalkan.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return InquiriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInquiries::route('/'),
            'edit' => EditInquiry::route('/{record}/edit'),
        ];
    }
}
