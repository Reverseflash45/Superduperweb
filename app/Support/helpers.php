<?php

use App\Models\Setting;

if (! function_exists('rupiah')) {
    /** Format nominal integer rupiah menjadi "Rp 1.500.000". */
    function rupiah(int|float|string|null $amount, string $prefix = 'Rp '): string
    {
        return $prefix.number_format((int) $amount, 0, ',', '.');
    }
}

if (! function_exists('setting')) {
    /** Ambil nilai pengaturan situs dari tabel settings. */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('wa_number')) {
    /** Normalkan nomor WhatsApp Indonesia ke format internasional tanpa tanda baca. */
    function wa_number(?string $number): string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }
}

if (! function_exists('wa_link')) {
    /** Bangun URL wa.me lengkap dengan pesan otomatis. */
    function wa_link(?string $message = null, ?string $number = null): string
    {
        $number = wa_number($number ?? setting('whatsapp_number', ''));

        if ($number === '') {
            return '#';
        }

        $url = 'https://wa.me/'.$number;

        return $message ? $url.'?text='.rawurlencode($message) : $url;
    }
}
