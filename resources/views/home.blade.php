@php
    // Dibangun di dalam blok PHP: string "@context" di dalam Blade akan
    // ditafsirkan sebagai directive kalau ditulis langsung di markup.
    $jsonFlags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    $businessSchema = json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => $site->name(),
        'description' => $site->metaDescription(),
        'url' => route('home'),
        'image' => asset('og-image.svg'),
        'areaServed' => 'ID',
        'availableLanguage' => 'id',
        'email' => $site->email(),
        'telephone' => $site->whatsappNumber() ? '+'.$site->whatsappNumber() : null,
        'sameAs' => $site->socialLinks()->pluck('url')->all(),
    ]), $jsonFlags);

    // Data terstruktur FAQ agar Google bisa menampilkannya di hasil pencarian.
    $faqSchema = $faqs->isEmpty() ? null : json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
        ])->all(),
    ], $jsonFlags);
@endphp

<x-layouts.public>
    @push('head')
        <script type="application/ld+json">{!! $businessSchema !!}</script>

        @if ($faqSchema)
            <script type="application/ld+json">{!! $faqSchema !!}</script>
        @endif
    @endpush

    @include('partials.sections.hero')
    @include('partials.sections.services')
    @include('partials.sections.tech-stack')
    @include('partials.sections.statistics')
    @include('partials.sections.packages')
    @include('partials.sections.portfolio')
    @include('partials.sections.process')
    @include('partials.sections.testimonials')
    @include('partials.sections.faq')
    @include('partials.sections.payment-methods')
    @include('partials.sections.cta')
</x-layouts.public>
