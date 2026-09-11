@php
    use App\Filament\Resources\Projects\ProjectResource;

    $project = $payment->project;
@endphp

<x-mail::message>
# Bukti pembayaran baru

Klien baru saja mengunggah bukti pembayaran lewat halaman invoice. Pembayaran ini **belum dihitung sebagai omzet** sampai kamu verifikasi.

<x-mail::panel>
**{{ rupiah($payment->amount) }}** — {{ $payment->type->getLabel() }}

Proyek: {{ $project?->title }}
Klien: {{ $project?->client?->name }}@if ($project?->client?->business_name) ({{ $project->client->business_name }})@endif
</x-mail::panel>

| Keterangan | Isi |
|:-----------|:----|
| Metode | {{ $payment->method->getLabel() }}@if ($payment->bankAccount) — {{ $payment->bankAccount->bank_name }}@endif |
| Tanggal bayar | {{ $payment->paid_at?->translatedFormat('d F Y') ?: '-' }} |
| Sisa tagihan saat ini | {{ $project ? rupiah($project->remaining) : '-' }} |
@if ($payment->note)
| Catatan klien | {{ $payment->note }} |
@endif

@if ($project)
<x-mail::button :url="ProjectResource::getUrl('edit', ['record' => $project])">
Cek dan verifikasi di panel admin
</x-mail::button>
@endif

Bukti pembayaran tersimpan di penyimpanan privat dan hanya bisa diunduh dari panel admin.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
