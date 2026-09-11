@props([
    'title' => null,
    'description' => null,
    'ogImage' => null,
    'noindex' => false,
    'showHeader' => true,
    'showFooter' => true,
    'showFloatingWa' => true,
])

@php
    $metaTitle = filled($title) ? $title.' | '.$site->name() : $site->name().' — Jasa Pembuatan Website & Aplikasi';
    $metaDescription = $description ?: $site->metaDescription();
    $ogImage = $ogImage ?: asset('og-image.svg');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">

    @if ($noindex)
        {{-- Halaman invoice bersifat privat: jangan diindeks mesin pencari. --}}
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $site->name() }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <meta name="theme-color" content="#2563EB">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if ($gaId = $site->googleAnalyticsId())
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @json($gaId));
        </script>
    @endif

    @stack('head')
</head>
<body class="min-h-screen bg-canvas font-sans text-ink antialiased">
    <a href="#konten"
       class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-white">
        Lewati ke konten utama
    </a>

    @includeWhen($showHeader, 'partials.header')

    <main id="konten">
        {{ $slot }}
    </main>

    @includeWhen($showFooter, 'partials.footer')

    @includeWhen($showFloatingWa && $site->whatsappNumber() !== '', 'partials.floating-whatsapp')
</body>
</html>
