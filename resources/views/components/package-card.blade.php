@props(['package'])

@php
    $popular = $package->is_popular;

    $waMessage = "Halo SuperDuperWeb, saya tertarik dengan paket {$package->name}. Boleh minta penjelasan lebih lanjut?";
@endphp

<article @class([
    'relative flex h-full flex-col rounded-2xl border bg-white p-6 transition',
    'border-brand-600 shadow-lg shadow-brand-600/10 ring-1 ring-brand-600' => $popular,
    'border-slate-200 hover:border-brand-200 hover:shadow-md' => ! $popular,
])>
    @if ($popular)
        <span class="absolute -top-3 left-6 inline-flex items-center rounded-full bg-accent-400 px-3 py-1 text-xs font-extrabold uppercase tracking-wide text-ink shadow-sm">
            Paling Populer
        </span>
    @endif

    <h3 class="text-xl font-extrabold text-ink">{{ $package->name }}</h3>

    @if ($package->tagline)
        <p class="mt-1.5 text-sm leading-relaxed text-slate-600">{{ $package->tagline }}</p>
    @endif

    <div class="mt-5">
        @if ($package->price === null)
            <p class="text-3xl font-extrabold tracking-tight text-ink">Hubungi kami</p>
            <p class="mt-1 text-sm text-slate-500">Harga menyesuaikan kebutuhan</p>
        @else
            @if ($package->is_starting_price)
                <p class="text-sm font-semibold text-slate-500">mulai dari</p>
            @endif
            <p class="text-3xl font-extrabold tracking-tight text-ink">{{ rupiah($package->price) }}</p>
        @endif
    </div>

    <dl class="mt-5 grid grid-cols-2 gap-3 border-y border-slate-100 py-4 text-sm">
        <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pengerjaan</dt>
            <dd class="mt-1 font-semibold text-ink">{{ $package->duration_estimate ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Revisi</dt>
            <dd class="mt-1 font-semibold text-ink">{{ $package->revision_count ?: '-' }}</dd>
        </div>
    </dl>

    @if (filled($package->features))
        <ul role="list" class="mt-5 space-y-2.5 text-sm">
            @foreach ($package->features as $feature)
                <li class="flex gap-2.5">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="leading-relaxed text-slate-700">{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="mt-auto space-y-2.5 pt-6">
        <a href="{{ wa_link($waMessage) }}" target="_blank" rel="noopener"
           @class([
               'inline-flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-bold transition',
               'bg-accent-400 text-ink hover:bg-accent-300' => $popular,
               'bg-brand-600 text-white hover:bg-brand-700' => ! $popular,
           ])>
            <x-icon.whatsapp class="h-4 w-4" />
            Pilih {{ $package->name }}
        </a>

        @if ($package->demo_url)
            <a href="{{ $package->demo_url }}" target="_blank" rel="noopener"
               class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-brand-200 hover:text-brand-700">
                Lihat demo
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H18v4.5M18 6l-7.5 7.5M16.5 14v3.5A1.5 1.5 0 0 1 15 19H6.5A1.5 1.5 0 0 1 5 17.5V9a1.5 1.5 0 0 1 1.5-1.5H10" />
                </svg>
            </a>
        @endif
    </div>
</article>
