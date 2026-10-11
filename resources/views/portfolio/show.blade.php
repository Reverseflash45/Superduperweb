@php
    $thumbnail = $portfolio->thumbnail ? Storage::disk('public')->url($portfolio->thumbnail) : null;

    $gallery = $portfolio->images
        ->map(fn ($image) => [
            'url' => Storage::disk('public')->url($image->path),
            'caption' => $image->caption,
        ])
        ->values();

    $waMessage = "Halo SuperDuperWeb, saya lihat proyek \"{$portfolio->title}\" di portofolio. Saya tertarik bikin yang serupa untuk usaha saya.";
@endphp

<x-layouts.public
    :title="$portfolio->title"
    :description="$portfolio->summary ?: Str::limit(strip_tags((string) $portfolio->problem), 155)"
    :og-image="$thumbnail">

    <article>
        <section class="border-b border-slate-200 bg-white">
            <div class="container-page py-10 sm:py-14">
                <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
                    <ol class="flex flex-wrap items-center gap-2">
                        <li><a href="{{ route('home') }}" class="transition hover:text-brand-700">Beranda</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ route('portfolio.index') }}" class="transition hover:text-brand-700">Portofolio</a></li>
                        <li aria-hidden="true">/</li>
                        <li class="font-semibold text-slate-700" aria-current="page">{{ $portfolio->title }}</li>
                    </ol>
                </nav>

                <div class="mt-5 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-bold text-brand-700 ring-1 ring-inset ring-brand-200">
                        {{ $portfolio->category->getLabel() }}
                    </span>
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                        {{ $portfolio->project_type->getLabel() }}
                    </span>
                    @if ($portfolio->completed_at)
                        <span class="text-xs font-semibold text-slate-500">
                            Selesai {{ $portfolio->completed_at->translatedFormat('F Y') }}
                        </span>
                    @endif
                </div>

                <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-ink sm:text-4xl lg:text-5xl">
                    {{ $portfolio->title }}
                </h1>

                @if ($portfolio->client_name)
                    <p class="mt-3 text-base font-semibold text-slate-600">{{ $portfolio->client_name }}</p>
                @endif

                @if ($portfolio->summary)
                    <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-600 sm:text-lg">{{ $portfolio->summary }}</p>
                @endif

                @if ($portfolio->demo_url)
                    <a href="{{ $portfolio->demo_url }}" target="_blank" rel="noopener"
                       class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-brand-700">
                        Buka demo
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H18v4.5M18 6l-7.5 7.5M16.5 14v3.5A1.5 1.5 0 0 1 15 19H6.5A1.5 1.5 0 0 1 5 17.5V9a1.5 1.5 0 0 1 1.5-1.5H10" />
                        </svg>
                    </a>
                @endif
            </div>
        </section>

        @if ($gallery->isNotEmpty())
            <section class="py-10 sm:py-14" x-data="{ active: 0 }">
                <div class="container-page">
                    <h2 class="sr-only">Galeri proyek</h2>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                        @foreach ($gallery as $index => $image)
                            <figure x-show="active === {{ $index }}" @if ($index > 0) x-cloak @endif>
                                <img src="{{ $image['url'] }}"
                                     alt="{{ $image['caption'] ?: $portfolio->title }}"
                                     @if ($index > 0) loading="lazy" @endif
                                     class="aspect-16/10 w-full bg-slate-100 object-cover">
                                @if ($image['caption'])
                                    <figcaption class="border-t border-slate-200 px-5 py-3 text-sm font-medium text-slate-600">
                                        {{ $image['caption'] }}
                                    </figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>

                    @if ($gallery->count() > 1)
                        <div class="mt-4 flex gap-3 overflow-x-auto pb-2" role="group" aria-label="Pilih gambar">
                            @foreach ($gallery as $index => $image)
                                <button type="button" @click="active = {{ $index }}"
                                        :class="active === {{ $index }} ? 'border-brand-600 ring-2 ring-brand-200' : 'border-slate-200 hover:border-brand-300'"
                                        class="relative h-20 w-32 shrink-0 overflow-hidden rounded-xl border-2 bg-white transition">
                                    <span class="sr-only">Lihat {{ $image['caption'] ?: 'gambar '.($index + 1) }}</span>
                                    <img src="{{ $image['url'] }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <section class="pb-14">
            <div class="container-page grid gap-10 lg:grid-cols-3">
                <div class="space-y-8 lg:col-span-2">
                    @if ($portfolio->problem)
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                            <h2 class="flex items-center gap-2.5 text-xl font-extrabold text-ink">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </span>
                                {{ $portfolio->project_type === \App\Enums\PortfolioType::Client ? 'Masalah klien' : 'Masalahnya' }}
                            </h2>
                            <p class="mt-4 whitespace-pre-line text-base leading-relaxed text-slate-700">{{ $portfolio->problem }}</p>
                        </div>
                    @endif

                    @if ($portfolio->solution)
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                            <h2 class="flex items-center gap-2.5 text-xl font-extrabold text-ink">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </span>
                                Solusi yang kami bangun
                            </h2>
                            <p class="mt-4 whitespace-pre-line text-base leading-relaxed text-slate-700">{{ $portfolio->solution }}</p>
                        </div>
                    @endif

                    @if (filled($portfolio->features))
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                            <h2 class="text-xl font-extrabold text-ink">Fitur yang dikerjakan</h2>
                            <ul role="list" class="mt-5 grid gap-3 sm:grid-cols-2">
                                @foreach ($portfolio->features as $feature)
                                    <li class="flex gap-2.5 text-sm">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="leading-relaxed text-slate-700">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <aside class="space-y-6">
                    @if (filled($portfolio->tech_stack))
                        <div class="rounded-2xl border border-slate-200 bg-white p-6">
                            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Tech stack</h2>
                            <ul class="mt-4 flex flex-wrap gap-2">
                                @foreach ($portfolio->tech_stack as $tech)
                                    <li class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700">{{ $tech }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="rounded-2xl bg-ink p-6 text-center">
                        <h2 class="text-lg font-extrabold text-white">Mau yang mirip seperti ini?</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-300">
                            Ceritakan kebutuhan usaha kamu. Kami bantu susun ruang lingkup dan kirim penawaran.
                        </p>
                        <a href="{{ wa_link($waMessage) }}" target="_blank" rel="noopener"
                           class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-accent-400 px-5 py-3 text-sm font-extrabold text-ink transition hover:bg-accent-300">
                            <x-icon.whatsapp class="h-4 w-4" />
                            Konsultasi Gratis
                        </a>
                    </div>
                </aside>
            </div>
        </section>

        @if ($related->isNotEmpty())
            <section class="pb-16">
                <div class="container-page">
                    <h2 class="text-2xl font-extrabold tracking-tight text-ink">Proyek lain di kategori yang sama</h2>

                    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($related as $item)
                            <x-portfolio-card :portfolio="$item" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>
</x-layouts.public>
