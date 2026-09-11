<x-layouts.public
    title="Konsultasi Gratis"
    description="Isi brief kebutuhan website atau aplikasi kamu. Gratis, tanpa kewajiban lanjut. Kami balas lewat WhatsApp.">

    <section class="border-b border-slate-200 bg-white">
        <div class="container-page py-12 sm:py-16">
            <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="transition hover:text-brand-700">Beranda</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-semibold text-slate-700" aria-current="page">Konsultasi</li>
                </ol>
            </nav>

            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-ink sm:text-4xl lg:text-5xl">
                Ceritakan kebutuhan kamu
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Isi brief singkat ini supaya kami bisa langsung paham konteksnya. Setelah dikirim,
                kamu akan diarahkan ke WhatsApp dengan ringkasan brief yang sudah terisi otomatis.
            </p>
        </div>
    </section>

    <section class="py-12 sm:py-16">
        <div class="container-page">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    @if ($waUrl = session('inquiry_sent'))
                        {{-- Brief sudah tersimpan. Buka WhatsApp otomatis, sediakan juga tombol manual. --}}
                        <div x-data="{ url: @js($waUrl) }" x-init="setTimeout(() => window.open(url, '_blank', 'noopener'), 600)"
                             class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 sm:p-8">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 h-6 w-6 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <div>
                                    <h2 class="text-lg font-extrabold text-emerald-900">Brief kamu sudah masuk</h2>
                                    <p class="mt-2 text-sm leading-relaxed text-emerald-800">
                                        Kami sedang membuka WhatsApp dengan ringkasan brief kamu. Kalau tidak terbuka otomatis,
                                        klik tombol di bawah ini.
                                    </p>

                                    <a :href="url" target="_blank" rel="noopener"
                                       class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[#25D366] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#1EBE5B]">
                                        <x-icon.whatsapp class="h-4 w-4" />
                                        Lanjut ke WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div role="alert" class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">
                            <h2 class="text-sm font-bold text-red-900">Ada yang perlu diperbaiki:</h2>
                            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-800">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('consultation.store') }}"
                          class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                        @csrf

                        {{-- Honeypot: tersembunyi dari manusia, biasanya diisi otomatis oleh bot. --}}
                        <div class="absolute left-[-9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                            <label for="website">Jangan isi kolom ini</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        {{-- Penanda waktu render, dipakai menolak kiriman yang terlalu cepat. --}}
                        <input type="hidden" name="rendered_at" value="{{ time() }}">

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-bold text-ink">
                                    Nama <span class="text-red-600" aria-hidden="true">*</span>
                                </label>
                                <input type="text" id="name" name="name" required autocomplete="name"
                                       value="{{ old('name') }}"
                                       @class([
                                           'mt-2 block w-full rounded-xl border px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200',
                                           'border-red-300' => $errors->has('name'),
                                           'border-slate-300' => ! $errors->has('name'),
                                       ])
                                       placeholder="Nama lengkap kamu">
                                @error('name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="business_name" class="block text-sm font-bold text-ink">Nama usaha / organisasi</label>
                                <input type="text" id="business_name" name="business_name" autocomplete="organization"
                                       value="{{ old('business_name') }}"
                                       class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
                                       placeholder="Kosongkan kalau belum ada">
                            </div>

                            <div>
                                <label for="whatsapp" class="block text-sm font-bold text-ink">
                                    Nomor WhatsApp <span class="text-red-600" aria-hidden="true">*</span>
                                </label>
                                <input type="tel" id="whatsapp" name="whatsapp" required inputmode="tel" autocomplete="tel"
                                       value="{{ old('whatsapp') }}"
                                       @class([
                                           'mt-2 block w-full rounded-xl border px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200',
                                           'border-red-300' => $errors->has('whatsapp'),
                                           'border-slate-300' => ! $errors->has('whatsapp'),
                                       ])
                                       placeholder="0812xxxxxxxx">
                                <p class="mt-1.5 text-xs text-slate-500">Supaya kami bisa menghubungi kamu balik.</p>
                                @error('whatsapp')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-bold text-ink">Email</label>
                                <input type="email" id="email" name="email" autocomplete="email"
                                       value="{{ old('email') }}"
                                       class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
                                       placeholder="Opsional">
                                @error('email')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="service_type" class="block text-sm font-bold text-ink">
                                    Jenis layanan <span class="text-red-600" aria-hidden="true">*</span>
                                </label>
                                <select id="service_type" name="service_type" required
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base text-ink shadow-sm transition focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                                    <option value="">Pilih layanan</option>
                                    @foreach ($serviceTypes as $type)
                                        <option value="{{ $type }}" @selected(old('service_type', $presetService) === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('service_type')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="budget_range" class="block text-sm font-bold text-ink">Kisaran budget</label>
                                <select id="budget_range" name="budget_range"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base text-ink shadow-sm transition focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                                    <option value="">Pilih kisaran</option>
                                    @foreach ($budgetRanges as $range)
                                        <option value="{{ $range }}" @selected(old('budget_range') === $range)>{{ $range }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1.5 text-xs text-slate-500">Membantu kami menyusun penawaran yang realistis.</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="deadline" class="block text-sm font-bold text-ink">Target selesai</label>
                                <select id="deadline" name="deadline"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base text-ink shadow-sm transition focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                                    <option value="">Pilih target waktu</option>
                                    @foreach ($deadlines as $deadline)
                                        <option value="{{ $deadline }}" @selected(old('deadline') === $deadline)>{{ $deadline }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="description" class="block text-sm font-bold text-ink">
                                    Deskripsi kebutuhan <span class="text-red-600" aria-hidden="true">*</span>
                                </label>
                                <textarea id="description" name="description" required rows="6"
                                          @class([
                                              'mt-2 block w-full rounded-xl border px-4 py-3 text-base leading-relaxed text-ink shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200',
                                              'border-red-300' => $errors->has('description'),
                                              'border-slate-300' => ! $errors->has('description'),
                                          ])
                                          placeholder="Contoh: Saya punya toko bangunan di Bogor. Ingin website supaya pelanggan bisa lihat daftar barang dan harga tanpa perlu datang ke toko dulu. Sudah ada logo dan foto produk.">{{ old('description') }}</textarea>
                                <p class="mt-1.5 text-xs text-slate-500">Makin detail makin bagus. Minimal 20 karakter.</p>
                                @error('description')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit"
                                class="mt-7 inline-flex w-full items-center justify-center gap-2.5 rounded-xl bg-accent-400 px-6 py-4 text-base font-extrabold text-ink transition hover:bg-accent-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent-500 sm:w-auto">
                            <x-icon.whatsapp class="h-5 w-5" />
                            Kirim Brief & Lanjut ke WhatsApp
                        </button>

                        <p class="mt-4 text-xs leading-relaxed text-slate-500">
                            Dengan mengirim brief ini kamu menyetujui
                            <a href="{{ route('terms') }}" class="font-semibold text-brand-700 underline underline-offset-2">Syarat &amp; Ketentuan</a>
                            kami. Data kamu hanya dipakai untuk keperluan penawaran, tidak dibagikan ke pihak lain.
                        </p>
                    </form>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <h2 class="text-lg font-extrabold text-ink">Setelah kirim brief</h2>
                        <ol class="mt-4 space-y-4">
                            @foreach ([
                                'Kami baca brief kamu dan hubungi lewat WhatsApp, biasanya di hari yang sama pada jam kerja.',
                                'Kalau perlu, kita lanjut ngobrol lewat Google Meet untuk menggali detailnya.',
                                'Kami kirim penawaran berisi ruang lingkup, harga, dan estimasi waktu pengerjaan.',
                            ] as $index => $step)
                                <li class="flex gap-3">
                                    <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-sm font-extrabold text-brand-700">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-sm leading-relaxed text-slate-600">{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="rounded-2xl border border-brand-200 bg-brand-50 p-6">
                        <h2 class="text-base font-extrabold text-ink">Mau langsung chat saja?</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Tidak masalah. Form ini cuma untuk mempercepat, bukan syarat wajib.
                        </p>
                        <a href="{{ wa_link('Halo SuperDuperWeb, saya mau konsultasi gratis soal website/aplikasi untuk usaha saya.') }}"
                           target="_blank" rel="noopener"
                           class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-brand-700">
                            <x-icon.whatsapp class="h-4 w-4" />
                            Chat WhatsApp
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
