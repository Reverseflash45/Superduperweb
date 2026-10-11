@if ($portfolios->isNotEmpty())
    <section id="portofolio" class="scroll-mt-20 py-16 sm:py-20">
        <div class="container-page">
            <x-section-heading
                eyebrow="Portofolio"
                title="Proyek yang sudah kami bangun"
                subtitle="Setiap proyek punya cerita: masalah apa yang diselesaikan dan bagaimana caranya. Sebagian bisa langsung kamu coba." />

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($portfolios as $portfolio)
                    <x-portfolio-card :portfolio="$portfolio" />
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('portfolio.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-ink transition hover:border-brand-300 hover:text-brand-700">
                    Lihat semua portofolio
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endif
