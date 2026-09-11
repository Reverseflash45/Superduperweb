<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreInquiryRequest extends FormRequest
{
    /** Pilihan yang ditawarkan di form, sekaligus jadi daftar nilai yang sah. */
    public const SERVICE_TYPES = [
        'Landing Page',
        'Company Profile',
        'Toko Online',
        'Sistem / Aplikasi Web Custom',
        'Aplikasi Mobile Android & iOS',
        'Maintenance Bulanan',
        'Belum tahu, mau konsultasi dulu',
    ];

    public const BUDGET_RANGES = [
        'Di bawah Rp 3 juta',
        'Rp 3 juta - Rp 5 juta',
        'Rp 5 juta - Rp 10 juta',
        'Rp 10 juta - Rp 25 juta',
        'Di atas Rp 25 juta',
        'Belum ada gambaran',
    ];

    public const DEADLINES = [
        'Secepatnya',
        'Dalam 1 bulan',
        'Dalam 2-3 bulan',
        'Lebih dari 3 bulan',
        'Fleksibel',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{8,}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'service_type' => ['required', 'string', 'in:'.implode(',', self::SERVICE_TYPES)],
            'budget_range' => ['nullable', 'string', 'in:'.implode(',', self::BUDGET_RANGES)],
            'deadline' => ['nullable', 'string', 'in:'.implode(',', self::DEADLINES)],
            'description' => ['required', 'string', 'min:20', 'max:3000'],

            // Honeypot: field tersembunyi yang hanya diisi bot.
            'website' => ['prohibited'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'business_name' => 'nama usaha',
            'whatsapp' => 'nomor WhatsApp',
            'email' => 'email',
            'service_type' => 'jenis layanan',
            'budget_range' => 'kisaran budget',
            'deadline' => 'target selesai',
            'description' => 'deskripsi kebutuhan',
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, dan tanda + - ( ).',
            'description.min' => 'Ceritakan kebutuhan kamu sedikit lebih detail, minimal 20 karakter.',
            'website.prohibited' => 'Pengiriman ditolak.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'business_name' => trim((string) $this->input('business_name')) ?: null,
            'email' => trim((string) $this->input('email')) ?: null,
        ]);
    }

    /**
     * Lapisan anti-spam kedua: form yang dikirim kurang dari 3 detik sejak dibuka
     * hampir pasti bukan diisi manusia.
     */
    protected function passedValidation(): void
    {
        $renderedAt = (int) $this->input('rendered_at');

        if ($renderedAt > 0 && (time() - $renderedAt) < 3) {
            throw ValidationException::withMessages([
                'description' => 'Form terkirim terlalu cepat. Coba kirim ulang sebentar lagi.',
            ]);
        }
    }
}
