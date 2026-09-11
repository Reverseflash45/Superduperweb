<section class="relative overflow-hidden">
    {{-- Ornamen latar; disembunyikan dari pembaca layar. --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-brand-100 blur-3xl sm:h-96 sm:w-96"></div>
        <div class="absolute -bottom-32 -left-24 h-72 w-72 rounded-full bg-accent-100 blur-3xl"></div>
    </div>

    <div class="container-page py-16 sm:py-24 lg:py-28">
        <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-8">
            <div class="lg:col-span-7">
                @if ($badge = setting('hero_badge'))
                    <p class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-3.5 py-1.5 text-xs font-bold uppercase tracking-wide text-brand-700 sm:text-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-brand-600"></span>
                        </span>
                        {{ $badge }}
                    </p>
                @endif

                <h1 class="mt-5 text-4xl font-extrabold leading-[1.1] tracking-tight text-ink sm:text-5xl lg:text-6xl">
                    {{ setting('hero_title', 'Website & Aplikasi Super, Bikin Usaha Kamu Makin Duper') }}
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    {{ setting('hero_subtitle', 'Kami bantu UMKM dan organisasi di Indonesia punya website dan aplikasi yang cepat, rapi di HP, dan siap mendatangkan pelanggan.') }}
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ wa_link('Halo SuperDuperWeb, saya mau konsultasi gratis soal website/aplikasi untuk usaha saya.') }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-accent-400 px-6 py-4 text-base font-extrabold text-ink shadow-sm transition hover:bg-accent-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent-500">
                        <x-icon.whatsapp class="h-5 w-5" />
                        Konsultasi Gratis
                    </a>

                    <a href="#paket"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-6 py-4 text-base font-bold text-ink transition hover:border-brand-300 hover:text-brand-700">
                        Lihat Paket
                    </a>
                </div>

                <ul class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm font-medium text-slate-600">
                    @foreach (['Konsultasi gratis tanpa biaya', 'Source code jadi milik kamu', 'Garansi bug 30 hari'] as $point)
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-5">
                {{-- Mockup layar HP: memperkuat pesan bahwa hasilnya rapi di ponsel. --}}
                <div class="relative mx-auto w-full max-w-sm">
                    <div class="absolute inset-0 -z-10 translate-x-4 translate-y-4 rounded-3xl bg-brand-600/10" aria-hidden="true"></div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-900/5">
                        <div class="rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 p-5 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-brand-100">Toko Online</p>
                            <p class="mt-1 text-xl font-extrabold">Batik Ayu Nusantara</p>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-white/10 p-3">
                                    <p class="text-xs text-brand-100">Pesanan hari ini</p>
                                    <p class="mt-1 text-2xl font-extrabold">24</p>
                                </div>
                                <div class="rounded-xl bg-white/10 p-3">
                                    <p class="text-xs text-brand-100">Omzet</p>
                                    <p class="mt-1 text-2xl font-extrabold">8,4 jt</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2.5 px-2 pb-12 pt-4">
                            @foreach ([['Pesanan masuk', 'Rp 450.000'], ['Pembayaran QRIS', 'Berhasil'], ['Ongkir otomatis', 'JNE REG']] as [$label, $value])
                                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3.5 py-3">
                                    <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
                                    <span class="text-sm font-bold text-brand-700">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="absolute bottom-3 left-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-lg">
                        <p class="text-xs font-semibold text-slate-500">Skor mobile</p>
                        <p class="text-xl font-extrabold text-emerald-600">98/100</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
