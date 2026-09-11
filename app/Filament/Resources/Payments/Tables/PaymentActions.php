<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Aksi verifikasi, penolakan, dan unduh bukti pembayaran.
 * Dipakai bersama oleh menu Pembayaran dan relation manager di halaman proyek.
 */
class PaymentActions
{
    public static function verify(): Action
    {
        return Action::make('verifikasi')
            ->label('Verifikasi')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Verifikasi pembayaran')
            ->modalDescription('Setelah diverifikasi, nominal ini dihitung sebagai omzet dan mengurangi sisa tagihan klien.')
            ->modalSubmitActionLabel('Ya, verifikasi')
            ->visible(fn (Payment $record) => $record->status !== PaymentStatus::Verified)
            ->action(function (Payment $record) {
                $record->update([
                    'status' => PaymentStatus::Verified,
                    'verified_at' => now(),
                    'rejection_reason' => null,
                ]);

                Notification::make()
                    ->title('Pembayaran terverifikasi')
                    ->body(rupiah($record->amount).' dicatat sebagai omzet.')
                    ->success()
                    ->send();
            });
    }

    public static function reject(): Action
    {
        return Action::make('tolak')
            ->label('Tolak')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->visible(fn (Payment $record) => $record->status !== PaymentStatus::Rejected)
            ->modalHeading('Tolak pembayaran')
            ->modalSubmitActionLabel('Tolak pembayaran')
            ->schema([
                Textarea::make('rejection_reason')
                    ->label('Alasan ditolak')
                    ->required()
                    ->rows(3)
                    ->helperText('Alasan ini ditampilkan ke klien di halaman invoice, jadi tulis yang jelas dan sopan.'),
            ])
            ->action(function (Payment $record, array $data) {
                $record->update([
                    'status' => PaymentStatus::Rejected,
                    'rejection_reason' => $data['rejection_reason'],
                    'verified_at' => null,
                ]);

                Notification::make()
                    ->title('Pembayaran ditolak')
                    ->body('Alasan penolakan sudah tampil di halaman invoice klien.')
                    ->warning()
                    ->send();
            });
    }

    /** Bukti pembayaran ada di disk privat, jadi hanya bisa diunduh lewat aksi admin ini. */
    public static function downloadProof(): Action
    {
        return Action::make('unduhBukti')
            ->label('Bukti')
            ->icon('heroicon-o-paper-clip')
            ->color('gray')
            ->visible(fn (Payment $record) => filled($record->proof_path))
            ->action(function (Payment $record): ?StreamedResponse {
                $disk = Storage::disk('private');

                if (! $disk->exists($record->proof_path)) {
                    Notification::make()
                        ->title('Berkas bukti tidak ditemukan')
                        ->danger()
                        ->send();

                    return null;
                }

                return $disk->download(
                    $record->proof_path,
                    'bukti-pembayaran-'.$record->id.'.'.pathinfo($record->proof_path, PATHINFO_EXTENSION),
                );
            });
    }
}
