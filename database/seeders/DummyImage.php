<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;

/**
 * Pembuat gambar placeholder (SVG) untuk data dummy.
 * Dipakai supaya tampilan situs langsung terisi tanpa perlu aset asli.
 */
class DummyImage
{
    public static function make(string $path, string $label, string $from = '#2563EB', string $to = '#1D4ED8', int $width = 1200, int $height = 750): string
    {
        $id = 'g'.substr(md5($path), 0, 8);
        $label = htmlspecialchars($label, ENT_QUOTES);
        $fontSize = (int) max(28, min(72, $width / max(8, mb_strlen($label)) * 1.4));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" width="{$width}" height="{$height}" role="img" aria-label="{$label}">
  <defs>
    <linearGradient id="{$id}" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$from}"/>
      <stop offset="100%" stop-color="{$to}"/>
    </linearGradient>
  </defs>
  <rect width="{$width}" height="{$height}" fill="url(#{$id})"/>
  <circle cx="{$width}" cy="0" r="220" fill="#FACC15" fill-opacity="0.18"/>
  <circle cx="0" cy="{$height}" r="180" fill="#FFFFFF" fill-opacity="0.10"/>
  <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
        font-family="'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif"
        font-size="{$fontSize}" font-weight="700" fill="#FFFFFF">{$label}</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    /** Mock gambar QRIS untuk demo (bukan QR yang bisa dipindai). */
    public static function qris(string $path): string
    {
        $cells = '';
        mt_srand(2024);

        for ($row = 0; $row < 25; $row++) {
            for ($col = 0; $col < 25; $col++) {
                if (mt_rand(0, 100) < 45) {
                    $x = 40 + $col * 20;
                    $y = 120 + $row * 20;
                    $cells .= "<rect x=\"{$x}\" y=\"{$y}\" width=\"20\" height=\"20\" fill=\"#0F172A\"/>";
                }
            }
        }

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 580 700" width="580" height="700">
  <rect width="580" height="700" rx="24" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2"/>
  <rect x="0" y="0" width="580" height="90" rx="24" fill="#2563EB"/>
  <text x="290" y="58" text-anchor="middle" font-family="system-ui, sans-serif" font-size="34" font-weight="700" fill="#FFFFFF">QRIS</text>
  {$cells}
  <text x="290" y="660" text-anchor="middle" font-family="system-ui, sans-serif" font-size="24" font-weight="600" fill="#0F172A">SuperDuperWeb</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    /** Logo bank sederhana berbentuk wordmark. */
    public static function bankLogo(string $path, string $name, string $color): string
    {
        $name = htmlspecialchars($name, ENT_QUOTES);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 120" width="320" height="120">
  <rect width="320" height="120" rx="16" fill="#FFFFFF"/>
  <rect x="14" y="34" width="10" height="52" rx="5" fill="{$color}"/>
  <text x="42" y="76" font-family="system-ui, sans-serif" font-size="42" font-weight="800" fill="{$color}">{$name}</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }
}
