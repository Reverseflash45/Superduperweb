<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $terms = <<<'HTML'
        <h2>Syarat &amp; Ketentuan Layanan</h2>
        <p>Dengan menggunakan jasa SuperDuperWeb, klien dianggap telah membaca dan menyetujui ketentuan di bawah ini.</p>

        <h3>1. Uang Muka (DP)</h3>
        <p>Pengerjaan dimulai setelah DP minimal <strong>50%</strong> dari nilai deal diterima dan diverifikasi. DP yang sudah dibayarkan tidak dapat dikembalikan apabila pembatalan datang dari pihak klien setelah pengerjaan dimulai.</p>

        <h3>2. Revisi</h3>
        <p>Jumlah revisi mengikuti paket yang dipilih dan dihitung per putaran revisi, bukan per item. Revisi di luar kuota dikenakan biaya tambahan yang disepakati terlebih dahulu. Permintaan revisi diterima paling lambat <strong>14 hari</strong> setelah serah terima.</p>

        <h3>3. Fitur Tambahan</h3>
        <p>Permintaan fitur di luar kesepakatan awal akan dihitung sebagai pekerjaan tambahan dengan penawaran terpisah. Penambahan fitur dapat memperpanjang estimasi waktu pengerjaan.</p>

        <h3>4. Kepemilikan Source Code</h3>
        <p>Source code sepenuhnya menjadi milik klien setelah <strong>pelunasan 100%</strong>. Sebelum pelunasan, hak pakai bersifat sementara dan terbatas pada keperluan pengujian. SuperDuperWeb berhak menampilkan hasil pekerjaan sebagai portofolio kecuali klien meminta sebaliknya secara tertulis.</p>

        <h3>5. Garansi Bug</h3>
        <p>Garansi perbaikan bug diberikan <strong>30 hari</strong> sejak serah terima, mencakup kesalahan fungsi pada fitur yang ada di kesepakatan. Garansi tidak mencakup perubahan fitur, kerusakan akibat perubahan oleh pihak lain, kegagalan layanan hosting atau domain pihak ketiga, serta serangan keamanan di luar kendali kami.</p>

        <h3>6. Domain, Hosting, dan Lisensi</h3>
        <p>Biaya domain, hosting, dan lisensi pihak ketiga tidak termasuk dalam harga paket kecuali disebutkan lain, dan menjadi tanggung jawab klien untuk perpanjangannya.</p>

        <h3>7. Keterlambatan dari Pihak Klien</h3>
        <p>Estimasi waktu pengerjaan dihitung sejak materi berupa teks, gambar, logo, dan data diterima lengkap. Keterlambatan pengiriman materi otomatis memperpanjang tenggat pengerjaan.</p>
        HTML;

        Setting::putMany([
            'site_name' => 'SuperDuperWeb',
            'hero_badge' => 'Jasa Pembuatan Website & Aplikasi',
            'hero_title' => 'Website & Aplikasi Super, Bikin Usaha Kamu Makin Duper',
            'hero_subtitle' => 'Kami bantu UMKM dan organisasi di Indonesia punya website dan aplikasi yang cepat, rapi di HP, dan siap mendatangkan pelanggan. Konsultasi gratis, tanpa biaya tersembunyi.',
            'whatsapp_number' => '6281234567890',
            'business_email' => 'halo@superduperweb.id',
            'instagram_url' => 'https://instagram.com/superduperweb',
            'facebook_url' => 'https://facebook.com/superduperweb',
            'tiktok_url' => 'https://tiktok.com/@superduperweb',
            'qris_image' => '',
            'terms_content' => $terms,
            'google_analytics_id' => '',
            'min_completed_projects_for_stats' => '3',
            'meta_description' => 'SuperDuperWeb adalah jasa pembuatan website dan aplikasi untuk UMKM serta organisasi di Indonesia. Landing page, company profile, toko online, sistem custom, dan aplikasi mobile.',
        ]);
    }
}
