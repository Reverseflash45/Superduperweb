<?php

namespace App\Filament\Resources\Inquiries\Tables;

use App\Enums\InquiryStatus;
use App\Enums\ProjectStatus;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Package;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y, H:i')
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->business_name),

                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor disalin'),

                TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('budget_range')
                    ->label('Budget')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('deadline')
                    ->label('Target')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->label('Kebutuhan')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(InquiryStatus::class)
                    ->multiple(),

                SelectFilter::make('service_type')
                    ->label('Jenis layanan')
                    ->options(fn () => Inquiry::query()
                        ->distinct()
                        ->orderBy('service_type')
                        ->pluck('service_type', 'service_type')
                        ->all()),
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Inquiry $record) => wa_link(
                        "Halo {$record->name}, terima kasih sudah mengirim brief ke SuperDuperWeb. Saya mau bahas kebutuhan {$record->service_type} yang kamu sampaikan.",
                        $record->whatsapp,
                    ))
                    ->openUrlInNewTab()
                    ->after(fn (Inquiry $record) => $record->status === InquiryStatus::New
                        ? $record->update(['status' => InquiryStatus::Contacted])
                        : null),

                static::convertAction(),

                EditAction::make()->label('Detail'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada brief masuk')
            ->emptyStateDescription('Brief dari halaman /konsultasi akan otomatis muncul di sini.');
    }

    /**
     * Mengubah brief berstatus Deal menjadi data klien + proyek sekaligus.
     * Klien dengan nomor WhatsApp yang sama dipakai ulang, tidak diduplikasi.
     */
    private static function convertAction(): Action
    {
        return Action::make('jadikanProyek')
            ->label('Jadikan Proyek')
            ->icon('heroicon-o-rocket-launch')
            ->color('primary')
            ->visible(fn (Inquiry $record) => $record->status === InquiryStatus::Deal)
            ->modalHeading('Jadikan Proyek')
            ->modalDescription('Data klien dan proyek akan dibuat otomatis dari brief ini.')
            ->modalSubmitActionLabel('Buat proyek')
            ->fillForm(fn (Inquiry $record) => [
                'title' => trim($record->service_type.' '.($record->business_name ?: $record->name)),
                'start_date' => now()->toDateString(),
                'dp_percentage' => 50,
            ])
            ->schema([
                Select::make('package_id')
                    ->label('Paket')
                    ->options(fn () => Package::query()->ordered()->pluck('name', 'id')->all())
                    ->searchable()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $package = $state ? Package::find($state) : null;

                        if ($package?->price && blank($get('deal_price'))) {
                            $set('deal_price', $package->price);
                        }
                    })
                    ->helperText('Opsional. Harga paket otomatis mengisi harga deal, dan tetap bisa diubah.'),

                TextInput::make('title')
                    ->label('Judul proyek')
                    ->required()
                    ->maxLength(255),

                TextInput::make('deal_price')
                    ->label('Harga deal')
                    ->numeric()
                    ->minValue(0)
                    ->step(1000)
                    ->prefix('Rp')
                    ->required()
                    ->helperText('Isi angka saja tanpa titik, misal 3500000.'),

                TextInput::make('dp_percentage')
                    ->label('Persentase DP')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->required(),

                DatePicker::make('start_date')
                    ->label('Tanggal mulai')
                    ->native(false)
                    ->displayFormat('d F Y')
                    ->required(),

                DatePicker::make('deadline')
                    ->label('Deadline')
                    ->native(false)
                    ->displayFormat('d F Y')
                    ->afterOrEqual('start_date'),
            ])
            ->action(function (Inquiry $record, array $data) {
                $project = DB::transaction(function () use ($record, $data) {
                    $client = Client::query()->updateOrCreate(
                        ['whatsapp' => $record->whatsapp],
                        [
                            'name' => $record->name,
                            'business_name' => $record->business_name,
                            'email' => $record->email,
                        ],
                    );

                    return Project::query()->create([
                        'client_id' => $client->id,
                        'package_id' => $data['package_id'] ?? null,
                        'inquiry_id' => $record->id,
                        'title' => $data['title'],
                        'deal_price' => (int) $data['deal_price'],
                        'dp_percentage' => (int) $data['dp_percentage'],
                        'start_date' => $data['start_date'],
                        'deadline' => $data['deadline'] ?? null,
                        'status' => ProjectStatus::Queued,
                        'notes' => $record->description,
                    ]);
                });

                Notification::make()
                    ->title('Proyek berhasil dibuat')
                    ->body('Klien dan proyek sudah tersimpan. Kode invoice untuk klien sudah digenerate otomatis.')
                    ->success()
                    ->actions([
                        Action::make('buka')
                            ->label('Buka proyek')
                            ->url(ProjectResource::getUrl('edit', ['record' => $project]))
                            ->markAsRead(),
                    ])
                    ->send();
            });
    }
}
