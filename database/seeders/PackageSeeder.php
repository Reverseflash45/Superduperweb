<?php

namespace Database\Seeders;

use App\Enums\PackageType;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Landing Page',
                'type' => PackageType::Project,
                'tagline' => 'Satu halaman fokus jualan, cepat tayang.',
                'price' => 1_500_000,
                'is_starting_price' => false,
                'duration_estimate' => '5-7 hari kerja',
                'revision_count' => '2x revisi',
                'features' => [
                    '1 halaman panjang (maks. 8 section)',
                    'Desain custom, mobile-first',
                    'Tombol WhatsApp & form leads',
                    'Domain .com gratis 1 tahun',
                    'Hosting 1 tahun',
                    'SEO dasar + Google Analytics',
                    'Garansi bug 30 hari',
                ],
                'demo_url' => null,
                'is_popular' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Company Profile',
                'type' => PackageType::Project,
                'tagline' => 'Wajah resmi usaha kamu di internet.',
                'price' => 3_500_000,
                'is_starting_price' => false,
                'duration_estimate' => '10-14 hari kerja',
                'revision_count' => '3x revisi',
                'features' => [
                    'Sampai 8 halaman (Beranda, Tentang, Layanan, dll.)',
                    'Halaman blog / artikel',
                    'Galeri & portofolio',
                    'Integrasi WhatsApp & Google Maps',
                    'Domain .com + hosting 1 tahun',
                    'Email bisnis (nama@usahakamu.com)',
                    'SEO dasar + sitemap + Search Console',
                    'Garansi bug 30 hari',
                ],
                'demo_url' => null,
                'is_popular' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Toko Online',
                'type' => PackageType::Project,
                'tagline' => 'Terima order dan pembayaran otomatis.',
                'price' => 6_500_000,
                'is_starting_price' => true,
                'duration_estimate' => '14-21 hari kerja',
                'revision_count' => '3x revisi',
                'features' => [
                    'Katalog produk tanpa batas',
                    'Keranjang & checkout',
                    'Payment gateway (QRIS, VA, e-wallet)',
                    'Ongkir otomatis (RajaOngkir)',
                    'Kupon diskon & stok otomatis',
                    'Dashboard admin pesanan',
                    'Domain + hosting 1 tahun',
                    'Garansi bug 30 hari',
                ],
                'demo_url' => null,
                'is_popular' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Sistem / Aplikasi Web Custom',
                'type' => PackageType::Project,
                'tagline' => 'Dibangun sesuai alur kerja usaha kamu.',
                'price' => 9_000_000,
                'is_starting_price' => true,
                'duration_estimate' => '3-8 minggu',
                'revision_count' => '4x revisi',
                'features' => [
                    'Analisis kebutuhan & alur kerja',
                    'Multi-user dengan hak akses',
                    'Laporan & ekspor Excel / PDF',
                    'Contoh: POS, kasir, inventori, absensi, SIM sekolah',
                    'Dokumentasi & pelatihan pemakaian',
                    'Deploy ke hosting / VPS kamu',
                    'Garansi bug 60 hari',
                ],
                'demo_url' => null,
                'is_popular' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Aplikasi Mobile Android & iOS',
                'type' => PackageType::Project,
                'tagline' => 'Satu kode, dua platform, siap rilis di store.',
                'price' => 15_000_000,
                'is_starting_price' => true,
                'duration_estimate' => '6-12 minggu',
                'revision_count' => '4x revisi',
                'features' => [
                    'Dibangun dengan Flutter (Android + iOS)',
                    'Desain UI/UX custom di Figma',
                    'Backend API + panel admin',
                    'Push notification',
                    'Bantuan submit Play Store & App Store',
                    'Dokumentasi teknis',
                    'Garansi bug 60 hari',
                ],
                'demo_url' => null,
                'is_popular' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Maintenance Basic',
                'type' => PackageType::Maintenance,
                'tagline' => 'Website kamu tetap hidup dan aman.',
                'price' => 250_000,
                'is_starting_price' => false,
                'duration_estimate' => 'Per bulan',
                'revision_count' => '1x update konten / bulan',
                'features' => [
                    'Backup mingguan',
                    'Update keamanan & plugin',
                    'Monitoring uptime',
                    'Perbaikan bug ringan',
                    'Laporan bulanan',
                ],
                'demo_url' => null,
                'is_popular' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'Maintenance Pro',
                'type' => PackageType::Maintenance,
                'tagline' => 'Ada yang urus konten dan performa tiap bulan.',
                'price' => 650_000,
                'is_starting_price' => false,
                'duration_estimate' => 'Per bulan',
                'revision_count' => '4x update konten / bulan',
                'features' => [
                    'Semua fitur Maintenance Basic',
                    'Backup harian',
                    'Optimasi kecepatan berkala',
                    'Laporan SEO & traffic bulanan',
                    'Prioritas respons maks. 1x24 jam',
                    'Konsultasi 1 jam / bulan',
                ],
                'demo_url' => null,
                'is_popular' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Maintenance Bisnis',
                'type' => PackageType::Maintenance,
                'tagline' => 'Untuk toko online dan sistem yang dipakai harian.',
                'price' => 1_500_000,
                'is_starting_price' => true,
                'duration_estimate' => 'Per bulan',
                'revision_count' => 'Update konten tanpa batas wajar',
                'features' => [
                    'Semua fitur Maintenance Pro',
                    'Monitoring 24/7 + notifikasi downtime',
                    'Penambahan fitur kecil (maks. 8 jam kerja / bulan)',
                    'Manajemen server & hosting',
                    'Prioritas respons maks. 4 jam',
                    'Rapat evaluasi bulanan',
                ],
                'demo_url' => null,
                'is_popular' => false,
                'sort_order' => 8,
            ],
        ];

        foreach ($packages as $package) {
            $package['slug'] = Str::slug($package['name']);
            $package['is_active'] = true;

            Package::query()->updateOrCreate(['slug' => $package['slug']], $package);
        }
    }
}
