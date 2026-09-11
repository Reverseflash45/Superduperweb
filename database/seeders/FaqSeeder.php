<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Berapa lama proses pembuatan website?',
                'answer' => 'Landing page biasanya selesai 5-7 hari kerja, company profile 10-14 hari kerja, dan toko online 14-21 hari kerja. Sistem custom menyesuaikan kompleksitas fitur. Hitungan hari dimulai setelah materi berupa teks, gambar, dan logo kami terima lengkap.',
            ],
            [
                'question' => 'Apakah harga sudah termasuk domain dan hosting?',
                'answer' => 'Paket Company Profile ke atas sudah termasuk domain .com dan hosting untuk tahun pertama. Untuk tahun berikutnya ada biaya perpanjangan yang kami informasikan jauh-jauh hari, atau bisa kamu kelola sendiri.',
            ],
            [
                'question' => 'Sistem pembayarannya bagaimana?',
                'answer' => 'DP minimal 50% di awal sebagai tanda jadi, sisanya dilunasi saat serah terima. Kami sediakan halaman invoice online berisi rincian tagihan, QRIS, dan rekening bank. Bukti transfer cukup diunggah di halaman itu.',
            ],
            [
                'question' => 'Apakah saya dapat source code-nya?',
                'answer' => 'Dapat. Source code sepenuhnya jadi milik kamu setelah pelunasan 100%, lengkap dengan akses hosting dan database. Tidak ada penguncian atau ketergantungan ke kami.',
            ],
            [
                'question' => 'Kalau ada bug setelah website jadi bagaimana?',
                'answer' => 'Ada garansi perbaikan bug 30 hari sejak serah terima, gratis. Setelah itu kamu bisa ambil paket maintenance bulanan atau perbaikan per kasus.',
            ],
            [
                'question' => 'Berapa kali saya bisa minta revisi?',
                'answer' => 'Jumlah revisi mengikuti paket yang dipilih dan dihitung per putaran revisi. Selama masih dalam scope yang disepakati, revisi tidak dipungut biaya tambahan.',
            ],
            [
                'question' => 'Bisa bikin aplikasi Android dan iOS sekaligus?',
                'answer' => 'Bisa. Kami pakai Flutter sehingga satu basis kode bisa jalan di Android dan iOS. Kami juga bantu proses submit ke Play Store dan App Store.',
            ],
            [
                'question' => 'Saya belum punya konten sama sekali, apa bisa dibantu?',
                'answer' => 'Bisa. Kami bantu susun struktur halaman dan draft teks berdasarkan hasil konsultasi, plus foto stok yang relevan. Kamu tinggal review dan setujui.',
            ],
            [
                'question' => 'Website-nya nanti muncul di Google?',
                'answer' => 'Semua paket sudah dipasang SEO dasar: meta title, deskripsi, struktur heading, sitemap, dan kecepatan yang dioptimalkan. Untuk peringkat teratas pada kata kunci tertentu perlu SEO lanjutan yang bisa kita bahas terpisah.',
            ],
            [
                'question' => 'Lokasi kamu di mana? Bisa kerja sama jarak jauh?',
                'answer' => 'Kami bekerja penuh secara daring, jadi klien dari kota mana pun di Indonesia bisa dilayani. Komunikasi lewat WhatsApp dan Google Meet, progres dikirim berkala.',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::query()->updateOrCreate(
                ['question' => $faq['question']],
                $faq + ['is_published' => true, 'sort_order' => $index + 1],
            );
        }
    }
}
