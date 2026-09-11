<footer class="mt-20 bg-ink text-slate-300">
    <div class="container-page py-14">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                    <img src="{{ asset('favicon.svg') }}" alt="" class="h-9 w-9" width="36" height="36">
                    <span class="text-lg font-extrabold tracking-tight text-white">
                        Super<span class="text-accent-400">Duper</span>Web
                    </span>
                </a>

                <p class="mt-4 max-w-md text-sm leading-relaxed text-slate-400">
                    Kami bantu UMKM dan organisasi di Indonesia punya website dan aplikasi yang rapi, cepat,
                    dan benar-benar dipakai pelanggan. Dikerjakan daring, klien dari kota mana pun bisa dilayani.
                </p>

                @if ($site->socialLinks()->isNotEmpty())
                    <div class="mt-6 flex items-center gap-3">
                        @foreach ($site->socialLinks() as $link)
                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener"
                               class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-slate-200 transition hover:bg-accent-400 hover:text-ink"
                               aria-label="{{ $link['label'] }}">
                                <x-dynamic-component :component="'icon.'.$link['key']" class="h-5 w-5" />
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-white">Layanan</h2>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @foreach (['Landing Page', 'Company Profile', 'Toko Online', 'Sistem / Aplikasi Web Custom', 'Aplikasi Mobile'] as $service)
                        <li>
                            <a href="{{ route('home') }}#layanan" class="text-slate-400 transition hover:text-white">{{ $service }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-white">Hubungi Kami</h2>
                <ul class="mt-4 space-y-3 text-sm">
                    @if ($site->whatsappNumber() !== '')
                        <li>
                            <a href="{{ wa_link('Halo SuperDuperWeb, saya mau tanya-tanya dulu.') }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                                <x-icon.whatsapp class="h-4 w-4 shrink-0" />
                                WhatsApp
                            </a>
                        </li>
                    @endif

                    @if ($email = $site->email())
                        <li>
                            <a href="mailto:{{ $email }}" class="inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                                <x-icon.mail class="h-4 w-4 shrink-0" />
                                {{ $email }}
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ route('portfolio.index') }}" class="text-slate-400 transition hover:text-white">Portofolio</a>
                    </li>
                    <li>
                        <a href="{{ route('consultation.create') }}" class="text-slate-400 transition hover:text-white">Isi Brief</a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="text-slate-400 transition hover:text-white">Syarat &amp; Ketentuan</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-3 border-t border-white/10 pt-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ now()->year }} {{ $site->name() }}. Seluruh hak cipta dilindungi.</p>
            <p>Dibuat di Indonesia untuk usaha Indonesia.</p>
        </div>
    </div>
</footer>
