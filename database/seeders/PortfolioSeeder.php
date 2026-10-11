<?php

namespace Database\Seeders;

use App\Enums\PortfolioCategory;
use App\Enums\PortfolioType;
use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Portofolio awal berisi proyek yang benar-benar sudah dibangun dan bisa dicek
 * (repo publik, sebagian bisa dicoba langsung). Tangkapan layarnya ada di
 * database/seeders/gambar dan disalin ke disk public saat seeding.
 *
 * Proyek klien ditambahkan lewat panel admin begitu ada.
 */
class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'UMR: Peta Upah vs Biaya Hidup',
                'gambar' => 'umr',
                'category' => PortfolioCategory::Web,
                'project_type' => PortfolioType::Personal,
                'summary' => 'Peta 514 kabupaten/kota yang membandingkan upah minimum 2026 dengan estimasi biaya hidup setempat.',
                'problem' => 'Angka UMK tersebar di puluhan SK gubernur, sementara orang yang mau pindah kerja ingin tahu satu hal sederhana: gaji segini cukup nggak untuk hidup di sana?',
                'solution' => 'Data UMK dari SK 38 provinsi dikumpulkan, garis kemiskinan BPS diekstrak dari PDF sebagai indeks harga, lalu semuanya digambar sebagai peta interaktif. Ketuk satu daerah untuk melihat struk bulanan yang bisa dibagikan sebagai gambar. Semua asumsi dibuka di halaman metode.',
                'features' => ['Peta interaktif 514 daerah', 'Hitung dengan UMK, gaji sendiri, atau upah kota asal', 'Profil lajang, berdua, keluarga', 'Gaji bersih setelah BPJS & PPh 21', 'Struk bulanan bisa dibagikan', 'Halaman metode & uji sensitivitas'],
                'tech_stack' => ['TypeScript', 'D3', 'Vite', 'Python'],
                'demo_url' => 'https://umr.raffstw.my.id',
                'completed_at' => '2026-10-11',
                'galeri' => ['thumb' => 'Peta dan struk bulanan', '1' => 'Halaman utama', '2' => 'Halaman metode'],
            ],
            [
                'title' => 'Pendekin',
                'gambar' => 'pendekin',
                'category' => PortfolioCategory::Web,
                'project_type' => PortfolioType::Personal,
                'summary' => 'Pemendek URL massal: sampai 20 tautan sekaligus, lengkap dengan QR code dan statistik klik.',
                'problem' => 'Pemendek URL gratis biasanya satu per satu, memaksa daftar akun, dan statistiknya ikut menghitung bot pratinjau WhatsApp sehingga angkanya menggelembung.',
                'solution' => 'Tempel sampai 20 URL atau impor CSV, langsung dapat tautan pendek, QR code, dan statistik per tautan. Semua penulisan lewat fungsi Postgres yang memvalidasi di basis data, bot pratinjau tidak dihitung, dan alamat IP tidak pernah disimpan.',
                'features' => ['Sampai 20 URL sekali proses', 'Impor CSV & ekspor hasil', 'Alias kustom & kedaluwarsa', 'QR code per tautan', 'Grafik klik, negara, perujuk, perangkat', 'Tanpa akun'],
                'tech_stack' => ['Next.js', 'TypeScript', 'Supabase', 'PostgreSQL', 'Tailwind CSS'],
                'demo_url' => 'https://pendek.raffstw.my.id',
                'completed_at' => '2026-09-21',
                'galeri' => ['thumb' => 'Hasil dan statistik klik', '1' => 'Form pemendek massal'],
            ],
            [
                'title' => 'Tracking',
                'gambar' => 'tracking',
                'category' => PortfolioCategory::Mobile,
                'project_type' => PortfolioType::Personal,
                'summary' => 'Aplikasi Android yang menyatukan jadwal kuliah, tugas, latihan, lari, makan, tidur, dan keuangan.',
                'problem' => 'Jadwal, tugas, catatan gym, dan pengeluaran tersebar di lima aplikasi berbeda, dan sebagian besar tidak bisa dibuka di ruang kuliah tanpa sinyal.',
                'solution' => 'Satu aplikasi Flutter dengan backend Supabase dan Row Level Security. Data terakhir disimpan lokal supaya tetap terbuka tanpa sinyal, dan catatan baru (misalnya lari 10 km di jalur tanpa sinyal) diantrekan sampai online. KRS dibaca AI lewat Edge Function, dengan OCR di HP sebagai cadangan.',
                'features' => ['Impor KRS dengan AI + OCR', 'Deteksi jadwal bentrok', 'Hitung repetisi lewat kamera', 'Lari GPS dengan rute di peta', 'Keuangan & baca struk', 'Mode offline, widget, kunci sidik jari', '1.059 test otomatis'],
                'tech_stack' => ['Flutter', 'Dart', 'Supabase', 'ML Kit', 'Edge Functions'],
                'demo_url' => 'https://github.com/Reverseflash45/Tracking/releases/latest',
                'completed_at' => '2026-10-06',
                'galeri' => ['thumb' => 'Tugas, Beranda, dan Workout'],
            ],
            [
                'title' => 'Postur',
                'gambar' => 'postur',
                'category' => PortfolioCategory::Mobile,
                'project_type' => PortfolioType::Personal,
                'summary' => 'Deteksi kesalahan postur squat dan push-up dari kamera, di Android dan di browser.',
                'problem' => 'Latihan sendiri tanpa pelatih membuat kesalahan bentuk (lutut masuk ke dalam, pinggul turun) tidak ketahuan sampai cedera.',
                'solution' => 'Pose 33 titik diolah menjadi deret waktu sudut sendi, dipotong per repetisi, lalu dinilai di perangkat oleh empat model: aturan ambang, Random Forest, 1D-CNN, dan LSTM. Versi web memakai MediaPipe dan berkas model yang sama, jadi bisa dicoba langsung dengan webcam tanpa instalasi.',
                'features' => ['Hitung repetisi & nilai bentuk per repetisi', 'Bagian tubuh yang salah disorot', 'Umpan balik suara', 'Versi web dengan webcam', 'Mode rekam & label dataset', 'Model berjalan di perangkat, video tidak diunggah'],
                'tech_stack' => ['Flutter', 'Python', 'TensorFlow Lite', 'MediaPipe', 'scikit-learn'],
                'demo_url' => 'https://postur-tau.vercel.app',
                'completed_at' => '2026-10-12',
                'galeri' => ['thumb' => 'Latihan, kerangka, dan ringkasan', '1' => 'Versi web'],
            ],
            [
                'title' => 'Sentimen Emiten',
                'gambar' => 'sentimen',
                'category' => PortfolioCategory::CustomSystem,
                'project_type' => PortfolioType::Personal,
                'summary' => 'Dasbor sentimen berita 45 emiten LQ45, disandingkan dengan pergerakan harga sahamnya.',
                'problem' => 'Berita emiten datang dari banyak portal sekaligus, dan sulit melihat apakah nada beritanya benar-benar sejalan dengan harga saham.',
                'solution' => 'Berita dikumpulkan dari 7 portal lewat RSS yang patuh robots.txt, dipetakan ke emiten, diverifikasi ke keterbukaan informasi BEI, lalu diberi skor sentimen harian dan diuji korelasinya dengan harga. Pengklasifikasinya bisa ditukar tanpa mengubah pipeline.',
                'features' => ['Pengumpulan berita otomatis dari 7 portal', 'Pemetaan berita ke emiten', 'Verifikasi ke pengumuman resmi BEI', 'Korelasi & uji Granger', 'Dasbor web + REST API', '252 test otomatis'],
                'tech_stack' => ['Python', 'FastAPI', 'SQLAlchemy', 'NLP'],
                'demo_url' => 'https://sentimen.raffstw.my.id',
                'completed_at' => '2026-10-04',
                'galeri' => ['thumb' => 'Peringkat dan grafik sentimen', '1' => 'Detail emiten'],
            ],
        ];

        foreach ($items as $index => $item) {
            $gambar = $item['gambar'];
            $galeri = $item['galeri'];
            unset($item['gambar'], $item['galeri']);

            $slug = Str::slug($item['title']);
            $item['slug'] = $slug;
            $item['client_name'] = null;
            $item['is_published'] = true;
            $item['sort_order'] = $index + 1;
            $item['thumbnail'] = self::salin("{$gambar}-thumb", $slug);

            $portfolio = Portfolio::query()->updateOrCreate(['slug' => $slug], $item);

            $portfolio->images()->delete();

            $urutan = 0;
            foreach ($galeri as $kunci => $caption) {
                $portfolio->images()->create([
                    'path' => $kunci === 'thumb' ? $item['thumbnail'] : self::salin("{$gambar}-{$kunci}", $slug),
                    'caption' => $caption,
                    'sort_order' => ++$urutan,
                ]);
            }
        }
    }

    /** Salin tangkapan layar dari repo ke disk public, kembalikan path-nya. */
    private static function salin(string $nama, string $slug): string
    {
        $tujuan = "portofolio/{$slug}/{$nama}.webp";
        Storage::disk('public')->put($tujuan, file_get_contents(__DIR__."/gambar/{$nama}.webp"));

        return $tujuan;
    }
}
