@if ($projectPackages->isNotEmpty() || $maintenancePackages->isNotEmpty())
    <section id="paket" class="scroll-mt-20 bg-white py-16 sm:py-20">
        <div class="container-page">
            <x-section-heading
                eyebrow="Paket & Harga"
                title="Harga jelas di depan, tanpa biaya siluman"
                subtitle="Semua paket sudah termasuk desain custom, bukan template pasaran. Kalau kebutuhan kamu di luar paket, kami buatkan penawaran terpisah." />

            <div x-data="{ tab: 'proyek' }" class="mt-10">
                @if ($projectPackages->isNotEmpty() && $maintenancePackages->isNotEmpty())
                    <div class="flex justify-center">
                        <div role="tablist" aria-label="Jenis paket"
                             class="inline-flex rounded-xl border border-slate-200 bg-canvas p-1.5">
                            <button type="button" role="tab" @click="tab = 'proyek'"
                                    :aria-selected="tab === 'proyek' ? 'true' : 'false'"
                                    :class="tab === 'proyek' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-brand-700'"
                                    class="rounded-lg px-4 py-2.5 text-sm font-bold transition sm:px-6">
                                Paket Proyek
                            </button>
                            <button type="button" role="tab" @click="tab = 'maintenance'"
                                    :aria-selected="tab === 'maintenance' ? 'true' : 'false'"
                                    :class="tab === 'maintenance' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-brand-700'"
                                    class="rounded-lg px-4 py-2.5 text-sm font-bold transition sm:px-6">
                                Maintenance Bulanan
                            </button>
                        </div>
                    </div>
                @endif

                @if ($projectPackages->isNotEmpty())
                    <div x-show="tab === 'proyek'" role="tabpanel" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($projectPackages as $package)
                            <x-package-card :package="$package" />
                        @endforeach
                    </div>
                @endif

                @if ($maintenancePackages->isNotEmpty())
                    <div x-show="tab === 'maintenance'" x-cloak role="tabpanel" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($maintenancePackages as $package)
                            <x-package-card :package="$package" />
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-12 rounded-2xl border border-dashed border-brand-300 bg-brand-50/60 p-6 text-center sm:p-8">
                <h3 class="text-lg font-bold text-ink">Kebutuhan kamu belum ada di daftar?</h3>
                <p class="mx-auto mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                    Ceritakan dulu apa yang kamu butuhkan. Kami bantu susun ruang lingkup dan kirim penawaran
                    yang pas dengan anggaran, tanpa kewajiban lanjut.
                </p>
                <a href="{{ route('consultation.create') }}"
                   class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-700">
                    Isi brief kebutuhan
                </a>
            </div>
        </div>
    </section>
@endif
