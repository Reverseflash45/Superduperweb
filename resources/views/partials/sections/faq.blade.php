@if ($faqs->isNotEmpty())
    <section id="faq" class="scroll-mt-20 bg-white py-16 sm:py-20">
        <div class="container-page">
            <x-section-heading
                eyebrow="FAQ"
                title="Pertanyaan yang paling sering masuk"
                subtitle="Kalau yang kamu tanyakan belum ada di sini, langsung chat saja. Kami balas cepat." />

            <div x-data="{ open: null }" class="mx-auto mt-12 max-w-3xl divide-y divide-slate-200 border-y border-slate-200">
                @foreach ($faqs as $index => $faq)
                    <div>
                        <h3>
                            <button type="button"
                                    @click="open = open === {{ $index }} ? null : {{ $index }}"
                                    :aria-expanded="open === {{ $index }} ? 'true' : 'false'"
                                    aria-controls="faq-panel-{{ $index }}"
                                    class="flex w-full items-start justify-between gap-4 py-5 text-left transition hover:text-brand-700">
                                <span class="text-base font-bold text-ink">{{ $faq->question }}</span>
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-brand-600 transition-transform duration-200"
                                     :class="open === {{ $index }} ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </h3>

                        <div id="faq-panel-{{ $index }}" x-show="open === {{ $index }}" x-cloak x-collapse>
                            <p class="pb-5 pr-9 text-sm leading-relaxed text-slate-600">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="mt-8 text-center text-sm text-slate-600">
                Masih ada yang mengganjal?
                <a href="{{ wa_link('Halo SuperDuperWeb, saya mau tanya sesuatu yang belum ada di FAQ.') }}"
                   target="_blank" rel="noopener"
                   class="font-bold text-brand-700 underline underline-offset-4 transition hover:text-brand-800">
                    Tanya langsung lewat WhatsApp
                </a>
            </p>
        </div>
    </section>
@endif
