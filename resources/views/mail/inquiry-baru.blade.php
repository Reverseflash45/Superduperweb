@php
    use App\Filament\Resources\Inquiries\InquiryResource;
@endphp

<x-mail::message>
# Brief baru masuk

Ada calon klien yang mengirim brief lewat halaman konsultasi.

<x-mail::panel>
**{{ $inquiry->name }}**@if ($inquiry->business_name) — {{ $inquiry->business_name }}@endif

WhatsApp: {{ $inquiry->whatsapp }}
@if ($inquiry->email)
Email: {{ $inquiry->email }}
@endif
</x-mail::panel>

| Keterangan | Isi |
|:-----------|:----|
| Jenis layanan | {{ $inquiry->service_type }} |
| Kisaran budget | {{ $inquiry->budget_range ?: '-' }} |
| Target selesai | {{ $inquiry->deadline ?: '-' }} |
| Masuk pada | {{ $inquiry->created_at->translatedFormat('d F Y, H:i') }} |

**Kebutuhan yang disampaikan:**

{{ $inquiry->description }}

<x-mail::button :url="wa_link('Halo ' . $inquiry->name . ', terima kasih sudah mengirim brief ke SuperDuperWeb.', $inquiry->whatsapp)" color="success">
Balas lewat WhatsApp
</x-mail::button>

<x-mail::button :url="InquiryResource::getUrl('edit', ['record' => $inquiry])">
Buka di panel admin
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
