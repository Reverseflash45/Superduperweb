@php
    use App\Enums\PortfolioCategory;
@endphp

<x-layouts.public
    title="Portofolio"
    description="Kumpulan website, aplikasi mobile, dan sistem custom yang sudah dikerjakan SuperDuperWeb untuk UMKM dan organisasi di Indonesia.">

    <section class="border-b border-slate-200 bg-white">
        <div class="container-page py-12 sm:py-16">
            <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="transition hover:text-brand-700">Beranda</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-semibold text-slate-700" aria-current="page">Portofolio</li>
                </ol>
            </nav>

            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-ink sm:text-4xl lg:text-5xl">
                Portofolio
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Setiap proyek di sini punya cerita lengkapnya: masalah yang dihadapi klien, solusi yang kami bangun,
                dan teknologi yang dipakai. Klik salah satu untuk melihat detailnya.
            </p>
        </div>
    </section>

    <section class="py-10 sm:py-14">
        <div class="container-page">
            @php
                $filters = collect([['label' => 'Semua', 'value' => null, 'count' => $totalCount]])
                    ->concat(collect(PortfolioCategory::cases())->map(fn ($case) => [
                        'label' => $case->getLabel(),
                        'value' => $case->value,
                        'count' => (int) ($counts[$case->value] ?? 0),
                    ]));
            @endphp

            <div class="flex flex-wrap gap-2" role="group" aria-label="Filter kategori portofolio">
                @foreach ($filters as $filter)
                    @php
                        $isActive = $activeCategory?->value === $filter['value'];
                        $url = $filter['value'] ? route('portfolio.index', ['kategori' => $filter['value']]) : route('portfolio.index');
                    @endphp

                    <a href="{{ $url }}"
                       @if ($isActive) aria-current="page" @endif
                       @class([
                           'inline-flex items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-bold transition',
                           'border-brand-600 bg-brand-600 text-white' => $isActive,
                           'border-slate-200 bg-white text-slate-700 hover:border-brand-300 hover:text-brand-700' => ! $isActive,
                       ])>
                        {{ $filter['label'] }}
                        <span @class([
                            'rounded-md px-1.5 py-0.5 text-xs font-bold',
                            'bg-white/20 text-white' => $isActive,
                            'bg-slate-100 text-slate-500' => ! $isActive,
                        ])>{{ $filter['count'] }}</span>
                    </a>
                @endforeach
            </div>

            @if ($portfolios->isEmpty())
                <div class="mt-12 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <h2 class="text-lg font-bold text-ink">Belum ada proyek di kategori ini</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">
                        Coba lihat kategori lain, atau langsung ceritakan kebutuhan kamu ke kami.
                    </p>
                    <a href="{{ route('portfolio.index') }}"
                       class="mt-5 inline-flex items-center justify-center rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700">
                        Lihat semua kategori
                    </a>
                </div>
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        <x-portfolio-card :portfolio="$portfolio" />
                    @endforeach
                </div>

                @if ($portfolios->hasPages())
                    <div class="mt-10">
                        {{ $portfolios->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>

    @include('partials.sections.cta')
</x-layouts.public>
