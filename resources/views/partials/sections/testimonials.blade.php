{{-- Section otomatis tidak dirender selama belum ada testimoni yang ditampilkan. --}}
@if ($testimonials->isNotEmpty())
    <section id="testimoni" class="scroll-mt-20 py-16 sm:py-20">
        <div class="container-page">
            <x-section-heading
                eyebrow="Testimoni"
                title="Kata mereka yang sudah bekerja sama"
                subtitle="Ditulis sendiri oleh pemilik usaha, apa adanya." />

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    @php
                        $avatar = $testimonial->avatar ? Storage::disk('public')->url($testimonial->avatar) : null;
                        $meta = collect([$testimonial->role, $testimonial->business_name])->filter()->implode(' · ');
                    @endphp

                    <figure class="flex h-full min-w-0 flex-col rounded-2xl border border-slate-200 bg-white p-6">
                        <div class="flex items-center gap-1" aria-label="Rating {{ $testimonial->rating }} dari 5">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $testimonial->rating ? 'text-accent-500' : 'text-slate-200' }}"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M10 1.5l2.6 5.27 5.82.85-4.21 4.1.99 5.79L10 14.78l-5.2 2.73.99-5.79-4.21-4.1 5.82-.85L10 1.5z" />
                                </svg>
                            @endfor
                        </div>

                        <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-slate-700">
                            <p>&ldquo;{{ $testimonial->content }}&rdquo;</p>
                        </blockquote>

                        <figcaption class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                            @if ($avatar)
                                <img src="{{ $avatar }}" alt="" loading="lazy" width="44" height="44"
                                     class="h-11 w-11 shrink-0 rounded-full object-cover">
                            @else
                                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-600 text-base font-extrabold text-white" aria-hidden="true">
                                    {{ Str::upper(Str::substr($testimonial->name, 0, 1)) }}
                                </span>
                            @endif

                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-ink">{{ $testimonial->name }}</p>
                                @if ($meta !== '')
                                    <p class="truncate text-xs text-slate-500">{{ $meta }}</p>
                                @endif
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif
