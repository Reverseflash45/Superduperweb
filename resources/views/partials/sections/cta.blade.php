<section class="pb-4 sm:pb-8">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-3xl bg-ink px-6 py-14 text-center sm:px-12 sm:py-16">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-brand-600/30 blur-3xl"></div>
                <div class="absolute -bottom-20 -left-16 h-64 w-64 rounded-full bg-accent-400/20 blur-3xl"></div>
            </div>

            <div class="relative mx-auto max-w-2xl">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Siap bikin usaha kamu makin duper?
                </h2>
                <p class="mt-4 text-base leading-relaxed text-slate-300 sm:text-lg">
                    Mulai dari obrolan singkat. Ceritakan usaha kamu, kami bantu petakan apa yang paling
                    dibutuhkan lebih dulu — tanpa biaya dan tanpa kewajiban lanjut.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ wa_link('Halo SuperDuperWeb, saya mau konsultasi gratis soal website/aplikasi untuk usaha saya.') }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-accent-400 px-6 py-4 text-base font-extrabold text-ink transition hover:bg-accent-300">
                        <x-icon.whatsapp class="h-5 w-5" />
                        Konsultasi Gratis
                    </a>

                    <a href="{{ route('consultation.create') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-white/20 px-6 py-4 text-base font-bold text-white transition hover:border-white/40 hover:bg-white/5">
                        Isi Brief Dulu
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
