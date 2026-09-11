{{--
    Angka dihitung otomatis dari database (proyek berstatus selesai dan klien yang punya proyek).
    Section ini tidak dirender sama sekali selama jumlah proyek selesai masih di bawah
    ambang minimum yang diatur admin.
--}}
@if ($statistics['visible'])
    <section class="py-16 sm:py-20">
        <div class="container-page">
            <div class="overflow-hidden rounded-3xl bg-brand-600 px-6 py-12 sm:px-12 sm:py-14">
                <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:items-center">
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                            Angka yang bisa kamu cek sendiri
                        </h2>
                        <p class="mt-3 text-sm leading-relaxed text-brand-100 sm:text-base">
                            Dihitung langsung dari data proyek kami, bukan angka karangan.
                            Semua proyek di bawah ini punya halaman portofolionya masing-masing.
                        </p>
                    </div>

                    <div class="text-center sm:text-left lg:text-center">
                        <p class="text-5xl font-extrabold tracking-tight text-accent-400 sm:text-6xl">
                            {{ $statistics['projects'] }}
                        </p>
                        <p class="mt-2 text-sm font-semibold uppercase tracking-wide text-brand-100">
                            Proyek selesai
                        </p>
                    </div>

                    <div class="text-center sm:text-left lg:text-center">
                        <p class="text-5xl font-extrabold tracking-tight text-accent-400 sm:text-6xl">
                            {{ $statistics['clients'] }}
                        </p>
                        <p class="mt-2 text-sm font-semibold uppercase tracking-wide text-brand-100">
                            Klien dilayani
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
