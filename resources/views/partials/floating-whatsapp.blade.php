{{-- Tombol WhatsApp mengambang, tampil di semua halaman publik. --}}
<div x-data="{ hint: false }" class="fixed bottom-5 right-5 z-50 flex items-center gap-3 print:hidden">
    <span x-show="hint" x-cloak x-transition.opacity
          class="hidden rounded-lg bg-ink px-3 py-2 text-sm font-semibold text-white shadow-lg sm:block">
        Konsultasi gratis, balas cepat
    </span>

    <a href="{{ wa_link('Halo SuperDuperWeb, saya mau konsultasi gratis soal website/aplikasi untuk usaha saya.') }}"
       target="_blank" rel="noopener"
       @mouseenter="hint = true" @mouseleave="hint = false" @focus="hint = true" @blur="hint = false"
       class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg shadow-emerald-600/30 transition hover:scale-105 hover:bg-[#1EBE5B] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#25D366]">
        <span class="sr-only">Konsultasi gratis lewat WhatsApp</span>
        <x-icon.whatsapp class="h-7 w-7" />
    </a>
</div>
