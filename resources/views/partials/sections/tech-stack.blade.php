@php
    // Logo digambar inline sebagai SVG agar tidak ada permintaan jaringan tambahan.
    $stack = [
        ['name' => 'JavaScript / TypeScript', 'short' => 'TS', 'color' => '#3178C6', 'text' => '#FFFFFF'],
        ['name' => 'React', 'short' => 'React', 'color' => '#20232A', 'text' => '#61DAFB'],
        ['name' => 'Laravel / PHP', 'short' => 'Laravel', 'color' => '#FF2D20', 'text' => '#FFFFFF'],
        ['name' => 'Go', 'short' => 'Go', 'color' => '#00ADD8', 'text' => '#FFFFFF'],
        ['name' => 'Flutter / Dart', 'short' => 'Flutter', 'color' => '#0468D7', 'text' => '#FFFFFF'],
        ['name' => 'MySQL', 'short' => 'MySQL', 'color' => '#00618A', 'text' => '#FFFFFF'],
        ['name' => 'Figma', 'short' => 'Figma', 'color' => '#1E1E1E', 'text' => '#FFFFFF'],
    ];
@endphp

<section class="border-y border-slate-200 bg-white py-12 sm:py-14">
    <div class="container-page">
        <p class="text-center text-sm font-bold uppercase tracking-wider text-slate-500">
            Teknologi yang kami pakai
        </p>

        <ul role="list" class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-7">
            @foreach ($stack as $tech)
                <li class="flex flex-col items-center gap-2.5 rounded-xl px-2 py-3 transition hover:bg-slate-50">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl text-xs font-extrabold"
                          style="background-color: {{ $tech['color'] }}; color: {{ $tech['text'] }};"
                          aria-hidden="true">
                        {{ Str::limit($tech['short'], 6, '') }}
                    </span>
                    <span class="text-center text-xs font-semibold leading-tight text-slate-600">{{ $tech['name'] }}</span>
                </li>
            @endforeach
        </ul>

        <p class="mt-8 text-center text-sm text-slate-500">
            Kami pilih teknologi sesuai kebutuhan proyek, bukan sesuai tren.
            Yang penting hasilnya cepat, aman, dan gampang dikembangkan nanti.
        </p>
    </div>
</section>
