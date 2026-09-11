{{--
    Hanya logo yang ditampilkan di sini. Nomor rekening dan detail pembayaran
    sengaja tidak pernah muncul di halaman publik, hanya di halaman invoice klien.
--}}
@if ($site->qrisUrl() || $bankAccounts->isNotEmpty())
    <section class="py-16 sm:py-20">
        <div class="container-page">
            <x-section-heading
                eyebrow="Metode Pembayaran"
                title="Bayar lewat QRIS atau transfer bank"
                subtitle="Detail pembayaran dan nomor rekening dikirim lewat halaman invoice pribadi kamu setelah deal, bukan dipajang di sini." />

            <div class="mx-auto mt-10 flex max-w-3xl flex-wrap items-center justify-center gap-4">
                @if ($qris = $site->qrisUrl())
                    <div class="flex h-20 w-32 items-center justify-center rounded-xl border border-slate-200 bg-white px-4">
                        <img src="{{ $qris }}" alt="QRIS" loading="lazy" class="max-h-14 w-auto object-contain">
                    </div>
                @endif

                @foreach ($bankAccounts as $bank)
                    <div class="flex h-20 w-32 items-center justify-center rounded-xl border border-slate-200 bg-white px-4">
                        @if ($bank->logo)
                            <img src="{{ Storage::disk('public')->url($bank->logo) }}"
                                 alt="{{ $bank->bank_name }}" loading="lazy"
                                 class="max-h-10 w-auto object-contain">
                        @else
                            <span class="text-base font-extrabold text-slate-700">{{ $bank->bank_name }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
