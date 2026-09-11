@php
    $steps = [
        ['title' => 'Konsultasi', 'description' => 'Ngobrol santai lewat WhatsApp atau Google Meet. Kami gali kebutuhan, target pengguna, dan anggaran kamu. Gratis, tanpa kewajiban lanjut.'],
        ['title' => 'Penawaran & DP', 'description' => 'Kami kirim penawaran berisi ruang lingkup, harga, dan estimasi waktu. Setelah disetujui, DP minimal 50% jadi tanda jadi.'],
        ['title' => 'Pengerjaan', 'description' => 'Kami mulai dari desain, lanjut ke pembuatan. Progres dikirim berkala supaya kamu tahu persis sudah sampai mana.'],
        ['title' => 'Revisi', 'description' => 'Kamu review hasilnya, kami perbaiki sesuai kuota revisi paket. Semua masukan dicatat supaya tidak ada yang terlewat.'],
        ['title' => 'Pelunasan', 'description' => 'Setelah kamu setuju hasilnya, sisa tagihan dilunasi lewat halaman invoice online berisi QRIS dan rekening bank.'],
        ['title' => 'Serah Terima', 'description' => 'Source code, akses hosting, dan domain diserahkan penuh. Kami temani masa garansi bug 30 hari dan bantu pelatihan pemakaian.'],
    ];
@endphp

<section id="cara-kerja" class="scroll-mt-20 bg-white py-16 sm:py-20">
    <div class="container-page">
        <x-section-heading
            eyebrow="Cara Kerja"
            title="Enam langkah, dari obrolan sampai serah terima"
            subtitle="Alurnya sama untuk semua proyek, jadi kamu selalu tahu apa yang terjadi berikutnya dan kapan harus membayar." />

        <ol class="relative mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($steps as $index => $step)
                <li class="relative flex h-full flex-col rounded-2xl border border-slate-200 bg-canvas p-6">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-lg font-extrabold text-white">
                            {{ $index + 1 }}
                        </span>
                        <h3 class="text-lg font-bold text-ink">{{ $step['title'] }}</h3>
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $step['description'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
