@php
    $navLinks = [
        ['label' => 'Layanan', 'url' => route('home').'#layanan'],
        ['label' => 'Paket & Harga', 'url' => route('home').'#paket'],
        ['label' => 'Portofolio', 'url' => route('portfolio.index')],
        ['label' => 'Cara Kerja', 'url' => route('home').'#cara-kerja'],
        ['label' => 'FAQ', 'url' => route('home').'#faq'],
    ];
@endphp

<header x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 12"
        class="sticky top-0 z-40 border-b transition-colors duration-200"
        :class="scrolled || open ? 'border-slate-200 bg-white/95 backdrop-blur' : 'border-transparent bg-canvas'">
    <div class="container-page">
        <div class="flex h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5" aria-label="{{ $site->name() }} — beranda">
                <img src="{{ asset('favicon.svg') }}" alt="" class="h-9 w-9" width="36" height="36">
                <span class="text-lg font-extrabold tracking-tight text-ink">
                    Super<span class="text-brand-600">Duper</span>Web
                </span>
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['url'] }}"
                       class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-brand-50 hover:text-brand-700">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{ route('consultation.create') }}"
                   class="rounded-lg px-3 py-2 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                    Isi Brief
                </a>
                <a href="{{ wa_link('Halo SuperDuperWeb, saya mau konsultasi gratis soal website/aplikasi untuk usaha saya.') }}"
                   target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-lg bg-accent-400 px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:bg-accent-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent-500">
                    <x-icon.whatsapp class="h-4 w-4" />
                    Konsultasi Gratis
                </a>
            </div>

            <button type="button"
                    @click="open = !open"
                    :aria-expanded="open ? 'true' : 'false'"
                    aria-controls="menu-mobile"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 lg:hidden">
                <span class="sr-only">Buka menu</span>
                <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div id="menu-mobile" x-show="open" x-cloak x-collapse class="border-t border-slate-200 bg-white lg:hidden">
        <nav class="container-page flex flex-col gap-1 py-4" aria-label="Navigasi mobile">
            @foreach ($navLinks as $link)
                <a href="{{ $link['url'] }}" @click="open = false"
                   class="rounded-lg px-3 py-3 text-base font-semibold text-slate-700 transition hover:bg-brand-50 hover:text-brand-700">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <a href="{{ route('consultation.create') }}" @click="open = false"
               class="rounded-lg px-3 py-3 text-base font-semibold text-brand-700 transition hover:bg-brand-50">
                Isi Brief
            </a>

            <a href="{{ wa_link('Halo SuperDuperWeb, saya mau konsultasi gratis soal website/aplikasi untuk usaha saya.') }}"
               target="_blank" rel="noopener"
               class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-accent-400 px-4 py-3 text-base font-bold text-ink">
                <x-icon.whatsapp class="h-5 w-5" />
                Konsultasi Gratis
            </a>
        </nav>
    </div>
</header>
