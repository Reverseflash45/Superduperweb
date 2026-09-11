@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'align' => 'center',
])

<div {{ $attributes->class([
    'max-w-3xl',
    'mx-auto text-center' => $align === 'center',
]) }}>
    @if ($eyebrow)
        <p class="text-sm font-bold uppercase tracking-wider text-brand-600">{{ $eyebrow }}</p>
    @endif

    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
        {{ $title }}
    </h2>

    @if ($subtitle)
        <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
            {{ $subtitle }}
        </p>
    @endif
</div>
