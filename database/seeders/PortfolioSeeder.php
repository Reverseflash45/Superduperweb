<?php

namespace Database\Seeders;

use App\Enums\PortfolioCategory;
use App\Enums\PortfolioType;
use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Kopi Kenangan Senja',
                'category' => PortfolioCategory::Web,
                'project_type' => PortfolioType::Client,
                'client_name' => 'Kopi Kenangan Senja, Yogyakarta',
                'summary' => 'Website company profile kedai kopi dengan menu digital dan reservasi meja lewat WhatsApp.',
                'problem' => 'Kedai hanya mengandalkan Instagram. Calon pelanggan sering bertanya hal yang sama soal menu, harga, dan jam buka, dan tim harus membalas satu per satu setiap hari.',
                'solution' => 'Kami bangun website ringan dengan menu digital yang bisa diperbarui sendiri lewat panel admin, tombol reservasi langsung ke WhatsApp berisi detail pesanan, dan halaman lokasi terintegrasi Google Maps.',
                'features' => ['Menu digital dengan kategori', 'Reservasi meja via WhatsApp', 'Galeri suasana kedai', 'Integrasi Google Maps', 'Panel admin untuk update menu'],
                'tech_stack' => ['Laravel', 'Tailwind CSS', 'Alpine.js', 'MySQL'],
                'demo_url' => 'https://demo.superduperweb.id/kopi-kenangan-senja',
                'completed_at' => '2026-07-18',
                'color' => ['#0F766E', '#134E4A'],
            ],
            [
                'title' => 'Batik Ayu Nusantara',
                'category' => PortfolioCategory::Web,
                'project_type' => PortfolioType::Client,
                'client_name' => 'Batik Ayu Nusantara, Pekalongan',
                'summary' => 'Toko online batik tulis dengan pembayaran otomatis dan ongkir real-time ke seluruh Indonesia.',
                'problem' => 'Penjualan masih lewat chat. Pemilik kewalahan menghitung ongkir manual, stok sering bentrok karena dijual di beberapa marketplace sekaligus.',
                'solution' => 'Kami buat toko online dengan stok terpusat, perhitungan ongkir otomatis dari RajaOngkir, dan pembayaran QRIS serta virtual account. Pesanan masuk langsung terkirim ke WhatsApp admin.',
                'features' => ['Katalog 200+ produk', 'Checkout & keranjang', 'Payment gateway QRIS dan VA', 'Ongkir otomatis RajaOngkir', 'Kupon diskon', 'Laporan penjualan'],
                'tech_stack' => ['Laravel', 'Livewire', 'Tailwind CSS', 'MySQL', 'Midtrans'],
                'demo_url' => 'https://demo.superduperweb.id/batik-ayu',
                'completed_at' => '2026-06-02',
                'color' => ['#9333EA', '#6B21A8'],
            ],
            [
                'title' => 'SIM Koperasi Sejahtera',
                'category' => PortfolioCategory::CustomSystem,
                'project_type' => PortfolioType::Client,
                'client_name' => 'Koperasi Karyawan Sejahtera, Semarang',
                'summary' => 'Sistem informasi simpan pinjam anggota koperasi, lengkap dengan angsuran dan laporan SHU.',
                'problem' => 'Pencatatan simpanan dan pinjaman masih pakai Excel yang dipegang satu orang. Rekap bulanan makan waktu berhari-hari dan rawan salah hitung bunga.',
                'solution' => 'Kami bangun sistem dengan data anggota terpusat, perhitungan angsuran dan bunga otomatis, riwayat transaksi per anggota, serta laporan bulanan dan SHU yang bisa diekspor ke Excel dan PDF.',
                'features' => ['Manajemen anggota', 'Simpanan pokok, wajib, sukarela', 'Pengajuan & persetujuan pinjaman', 'Jadwal angsuran otomatis', 'Laporan SHU', 'Hak akses bertingkat', 'Ekspor Excel & PDF'],
                'tech_stack' => ['Laravel', 'Filament', 'MySQL', 'Chart.js'],
                'demo_url' => null,
                'completed_at' => '2026-05-20',
                'color' => ['#2563EB', '#1E3A8A'],
            ],
            [
                'title' => 'Warung Pintar POS',
                'category' => PortfolioCategory::Mobile,
                'project_type' => PortfolioType::Client,
                'client_name' => 'Warung Pintar Grup, Surabaya',
                'summary' => 'Aplikasi kasir Android untuk 12 cabang warung, bisa jalan saat internet mati.',
                'problem' => 'Kasir di cabang sering kehilangan transaksi saat internet putus, dan pemilik tidak bisa melihat penjualan antar cabang secara real-time.',
                'solution' => 'Kami buat aplikasi Flutter dengan mode offline-first: transaksi disimpan lokal lalu tersinkron otomatis saat internet kembali. Pemilik punya dashboard web untuk memantau seluruh cabang.',
                'features' => ['Kasir offline-first', 'Sinkronisasi otomatis', 'Cetak struk Bluetooth', 'Manajemen stok per cabang', 'Dashboard pemilik', 'Laporan harian per kasir'],
                'tech_stack' => ['Flutter', 'Dart', 'Go', 'PostgreSQL', 'Figma'],
                'demo_url' => 'https://demo.superduperweb.id/warung-pintar',
                'completed_at' => '2026-04-11',
                'color' => ['#EA580C', '#9A3412'],
            ],
            [
                'title' => 'Yayasan Cahaya Harapan',
                'category' => PortfolioCategory::Web,
                'project_type' => PortfolioType::Client,
                'client_name' => 'Yayasan Cahaya Harapan, Bandung',
                'summary' => 'Website yayasan sosial dengan halaman donasi, laporan penggunaan dana, dan agenda kegiatan.',
                'problem' => 'Donatur ragu berdonasi karena tidak ada tempat resmi untuk melihat program dan laporan penggunaan dana. Semua info tersebar di grup WhatsApp.',
                'solution' => 'Kami buat website dengan halaman program donasi, progres terkumpul yang diperbarui otomatis, laporan transparansi yang bisa diunduh, dan agenda kegiatan yang dikelola pengurus sendiri.',
                'features' => ['Halaman program donasi', 'Progress bar dana terkumpul', 'Laporan transparansi unduhan', 'Agenda kegiatan', 'Galeri dokumentasi', 'Newsletter relawan'],
                'tech_stack' => ['Laravel', 'Tailwind CSS', 'Alpine.js', 'MySQL'],
                'demo_url' => 'https://demo.superduperweb.id/cahaya-harapan',
                'completed_at' => '2026-03-08',
                'color' => ['#0891B2', '#155E75'],
            ],
            [
                'title' => 'Absensi Wajah Sekolah',
                'category' => PortfolioCategory::CustomSystem,
                'project_type' => PortfolioType::Demo,
                'client_name' => null,
                'summary' => 'Demo sistem absensi siswa berbasis pengenalan wajah dengan notifikasi WhatsApp ke orang tua.',
                'problem' => 'Absensi manual dengan buku membuat rekap kehadiran terlambat dan orang tua baru tahu anaknya bolos saat pembagian rapor.',
                'solution' => 'Prototipe ini memakai kamera untuk mengenali wajah siswa saat masuk gerbang, mencatat kehadiran otomatis, dan mengirim notifikasi WhatsApp ke orang tua dalam hitungan detik.',
                'features' => ['Pengenalan wajah', 'Rekap kehadiran harian', 'Notifikasi WhatsApp ke orang tua', 'Rekap per kelas & mata pelajaran', 'Ekspor laporan bulanan'],
                'tech_stack' => ['Laravel', 'Go', 'Python', 'MySQL', 'Figma'],
                'demo_url' => 'https://demo.superduperweb.id/absensi-wajah',
                'completed_at' => '2026-02-14',
                'color' => ['#4F46E5', '#312E81'],
            ],
            [
                'title' => 'Tani Cerdas Marketplace',
                'category' => PortfolioCategory::Mobile,
                'project_type' => PortfolioType::Personal,
                'client_name' => null,
                'summary' => 'Proyek pribadi: aplikasi yang mempertemukan petani langsung dengan pembeli grosir.',
                'problem' => 'Petani kecil kesulitan menembus pembeli besar dan harus lewat banyak tengkulak, sehingga harga jual di tingkat petani jatuh.',
                'solution' => 'Aplikasi ini menampilkan harga pasar harian, memungkinkan petani memasang stok panen, dan pembeli grosir menawar langsung. Transaksi dijaga sistem escrow sederhana.',
                'features' => ['Harga pasar harian', 'Pasang stok panen', 'Negosiasi harga dalam aplikasi', 'Escrow pembayaran', 'Rating penjual & pembeli'],
                'tech_stack' => ['React Native', 'TypeScript', 'Go', 'PostgreSQL'],
                'demo_url' => 'https://demo.superduperweb.id/tani-cerdas',
                'completed_at' => '2026-01-22',
                'color' => ['#16A34A', '#14532D'],
            ],
            [
                'title' => 'Laundry Kilat Management',
                'category' => PortfolioCategory::CustomSystem,
                'project_type' => PortfolioType::Client,
                'client_name' => 'Laundry Kilat, Malang',
                'summary' => 'Sistem manajemen laundry dengan tracking status cucian dan notifikasi otomatis ke pelanggan.',
                'problem' => 'Pelanggan sering menelepon menanyakan cucian sudah selesai atau belum, dan nota kertas kerap hilang sehingga terjadi salah ambil.',
                'solution' => 'Kami buat sistem dengan nota digital berkode unik. Pelanggan bisa cek status lewat link, dan sistem mengirim notifikasi WhatsApp otomatis begitu cucian siap diambil.',
                'features' => ['Nota digital berkode QR', 'Tracking status cucian', 'Notifikasi WhatsApp otomatis', 'Manajemen layanan & harga', 'Laporan omzet harian', 'Member & poin loyalitas'],
                'tech_stack' => ['Laravel', 'Filament', 'Tailwind CSS', 'MySQL'],
                'demo_url' => 'https://demo.superduperweb.id/laundry-kilat',
                'completed_at' => '2025-12-05',
                'color' => ['#0EA5E9', '#075985'],
            ],
        ];

        foreach ($items as $index => $item) {
            [$from, $to] = $item['color'];
            unset($item['color']);

            $slug = Str::slug($item['title']);
            $item['slug'] = $slug;
            $item['is_published'] = true;
            $item['sort_order'] = $index + 1;
            $item['thumbnail'] = DummyImage::make("portofolio/{$slug}-thumb.svg", $item['title'], $from, $to);

            $portfolio = Portfolio::query()->updateOrCreate(['slug' => $slug], $item);

            $portfolio->images()->delete();

            foreach (['Tampilan Beranda', 'Halaman Utama Fitur', 'Panel Admin'] as $order => $caption) {
                $portfolio->images()->create([
                    'path' => DummyImage::make("portofolio/{$slug}-{$order}.svg", $caption, $from, $to, 1600, 1000),
                    'caption' => $caption,
                    'sort_order' => $order + 1,
                ]);
            }
        }
    }
}
