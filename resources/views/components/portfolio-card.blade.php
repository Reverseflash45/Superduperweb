@props(['portfolio'])

@php
    $thumbnail = $portfolio->thumbnail ? Storage::disk('public')->url($portfolio->thumbnail) : null;

    $typeClasses = match ($portfolio->project_type->value) {
        'klien' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'pribadi' => 'bg-sky-50 text-sky-700 ring-sky-200',
        default => 'bg-accent-100 text-amber-800 ring-amber-200',
    };
@endphp

<article class="group flex h-full min-w-0 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-lg hover:shadow-brand-600/5">
    <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="block aspect-16/10 overflow-hidden bg-slate-100">
        @if ($thumbnail)
            <img src="{{ $thumbnail }}" alt="Tampilan proyek {{ $portfolio->title }}" loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-brand-600 text-lg font-bold text-white">
                {{ $portfolio->title }}
            </div>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700 ring-1 ring-inset ring-brand-200">
                {{ $portfolio->category->getLabel() }}
            </span>
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $typeClasses }}">
                {{ $portfolio->project_type->getLabel() }}
            </span>
        </div>

        <h3 class="mt-3 text-lg font-bold leading-snug text-ink">
            <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="transition hover:text-brand-700">
                {{ $portfolio->title }}
            </a>
        </h3>

        @if ($portfolio->summary)
            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $portfolio->summary }}</p>
        @endif

        @if (filled($portfolio->tech_stack))
            <ul class="mt-4 flex flex-wrap gap-1.5">
                @foreach (array_slice($portfolio->tech_stack, 0, 4) as $tech)
                    <li class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">{{ $tech }}</li>
                @endforeach
                @if (count($portfolio->tech_stack) > 4)
                    <li class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-500">
                        +{{ count($portfolio->tech_stack) - 4 }}
                    </li>
                @endif
            </ul>
        @endif

        <a href="{{ route('portfolio.show', $portfolio->slug) }}"
           class="mt-auto inline-flex items-center gap-1.5 pt-5 text-sm font-bold text-brand-700 transition hover:gap-2.5 hover:text-brand-800">
            Lihat detail
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</article>
