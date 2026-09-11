@php
    $services = [
        [
            'title' => 'Landing Page',
            'description' => 'Satu halaman yang fokus mengubah pengunjung jadi calon pembeli. Cocok untuk promo, pre-order, dan rekrut reseller.',
            'points' => ['Tayang 5-7 hari kerja', 'Tombol WhatsApp & form leads'],
            'icon' => 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12',
        ],
        [
            'title' => 'Company Profile',
            'description' => 'Wajah resmi usaha kamu di internet. Profil, layanan, galeri, dan blog yang bisa kamu perbarui sendiri.',
            'points' => ['Sampai 8 halaman', 'Email bisnis + SEO dasar'],
            'icon' => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
        ],
        [
            'title' => 'Toko Online',
            'description' => 'Terima pesanan dan pembayaran otomatis 24 jam. Stok, ongkir, dan laporan penjualan terkelola dalam satu tempat.',
            'points' => ['Payment gateway & ongkir otomatis', 'Dashboard pesanan'],
            'icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z',
        ],
        [
            'title' => 'Sistem / Aplikasi Web Custom',
            'description' => 'Dibangun mengikuti alur kerja usaha kamu, bukan sebaliknya. POS, inventori, absensi, sampai sistem koperasi.',
            'points' => ['Multi-user & hak akses', 'Laporan ekspor Excel / PDF'],
            'icon' => 'M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z',
        ],
        [
            'title' => 'Aplikasi Mobile Android & iOS',
            'description' => 'Satu basis kode Flutter untuk dua platform sekaligus, lengkap dengan backend dan panel admin.',
            'points' => ['Bantuan submit ke Play Store & App Store', 'Push notification'],
            'icon' => 'M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3',
        ],
    ];
@endphp

<section id="layanan" class="scroll-mt-20 py-16 sm:py-20">
    <div class="container-page">
        <x-section-heading
            eyebrow="Layanan"
            title="Apa yang bisa kami bangun untuk kamu"
            subtitle="Dari satu halaman promo sampai sistem yang dipakai belasan cabang. Semua dikerjakan mobile-first, karena mayoritas pelanggan kamu membuka lewat HP." />

        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $index => $service)
                <article @class([
                    'group flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-lg hover:shadow-brand-600/5',
                    'lg:col-span-1 sm:col-span-2 lg:col-start-2' => $index === 4,
                ])>
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $service['icon'] }}" />
                        </svg>
                    </span>

                    <h3 class="mt-5 text-lg font-bold text-ink">{{ $service['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $service['description'] }}</p>

                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach ($service['points'] as $point)
                            <li class="flex gap-2 text-slate-700">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>

                    <a href="{{ wa_link('Halo SuperDuperWeb, saya mau tanya soal layanan '.$service['title'].'.') }}"
                       target="_blank" rel="noopener"
                       class="mt-auto inline-flex items-center gap-1.5 pt-5 text-sm font-bold text-brand-700 transition hover:gap-2.5">
                        Tanya soal layanan ini
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
