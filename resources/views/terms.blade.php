<x-layouts.public
    title="Syarat & Ketentuan"
    description="Ketentuan layanan SuperDuperWeb: uang muka, revisi, fitur tambahan, kepemilikan source code, dan garansi bug.">

    <section class="border-b border-slate-200 bg-white">
        <div class="container-page py-12 sm:py-16">
            <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="transition hover:text-brand-700">Beranda</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-semibold text-slate-700" aria-current="page">Syarat &amp; Ketentuan</li>
                </ol>
            </nav>

            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Syarat &amp; Ketentuan
            </h1>
            <p class="mt-3 text-sm text-slate-500">
                Terakhir diperbarui {{ optional(\App\Models\Setting::query()->where('key', 'terms_content')->value('updated_at'))->translatedFormat('d F Y') ?: now()->translatedFormat('d F Y') }}
            </p>
        </div>
    </section>

    <section class="py-12 sm:py-16">
        <div class="container-page">
            <div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 sm:p-10">
                @if (filled($content))
                    <div class="prose prose-slate max-w-none prose-headings:font-extrabold prose-headings:text-ink prose-h2:text-2xl prose-h3:text-lg prose-a:text-brand-700 prose-strong:text-ink">
                        {!! $content !!}
                    </div>
                @else
                    <p class="text-slate-600">
                        Isi Syarat &amp; Ketentuan belum diatur. Silakan lengkapi lewat panel admin pada menu Pengaturan Situs.
                    </p>
                @endif
            </div>

            <div class="mx-auto mt-8 max-w-3xl rounded-2xl border border-brand-200 bg-brand-50 p-6 text-center">
                <p class="text-sm leading-relaxed text-slate-700">
                    Ada bagian yang ingin kamu tanyakan sebelum deal?
                    <a href="{{ wa_link('Halo SuperDuperWeb, saya mau tanya soal Syarat & Ketentuan.') }}"
                       target="_blank" rel="noopener"
                       class="font-bold text-brand-700 underline underline-offset-4">Tanya lewat WhatsApp</a>
                    sebelum melakukan pembayaran.
                </p>
            </div>
        </div>
    </section>
</x-layouts.public>
