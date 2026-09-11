<?php

namespace Database\Seeders;

use App\Enums\ExpenseCategory;
use App\Enums\InquiryStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\ProjectStatus;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Package;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Data dummy sisi operasional: klien, brief masuk, proyek, pembayaran, dan biaya.
 * Tanggal dibuat relatif terhadap hari ini supaya grafik 12 bulan terakhir selalu terisi.
 */
class OperationalSeeder extends Seeder
{
    public function run(): void
    {
        $banks = BankAccount::query()->active()->ordered()->get();
        $bca = $banks->firstWhere('bank_name', 'BCA');
        $mandiri = $banks->firstWhere('bank_name', 'Mandiri');

        $packages = Package::query()->pluck('id', 'slug');
        $today = Carbon::today();

        $this->seedInquiries($today);

        $blueprints = [
            [
                'client' => ['name' => 'Rina Kusuma', 'business_name' => 'Kopi Kenangan Senja', 'whatsapp' => '081234567801', 'email' => 'rina@kopikenangansenja.id', 'address' => 'Jl. Kaliurang KM 5 No. 12, Sleman, DI Yogyakarta', 'notes' => 'Lebih nyaman dihubungi lewat WhatsApp sore hari.'],
                'project' => ['title' => 'Website Company Profile Kopi Kenangan Senja', 'package' => 'company-profile', 'deal_price' => 3_500_000, 'dp_percentage' => 50, 'months_ago' => 2, 'duration_days' => 14, 'status' => ProjectStatus::Completed],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Settlement, 'ratio' => 0.5, 'offset' => 14, 'method' => PaymentMethod::Qris, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Domain, 'amount' => 180_000, 'offset' => 1, 'description' => 'Domain kopikenangansenja.id 1 tahun'],
                    ['category' => ExpenseCategory::Hosting, 'amount' => 720_000, 'offset' => 1, 'description' => 'Hosting cloud 1 tahun'],
                ],
            ],
            [
                'client' => ['name' => 'Hendra Wijaya', 'business_name' => 'Batik Ayu Nusantara', 'whatsapp' => '081234567802', 'email' => 'hendra@batikayu.co.id', 'address' => 'Jl. Hayam Wuruk No. 88, Pekalongan, Jawa Tengah', 'notes' => 'Minta laporan penjualan dikirim tiap awal bulan.'],
                'project' => ['title' => 'Toko Online Batik Ayu Nusantara', 'package' => 'toko-online', 'deal_price' => 8_500_000, 'dp_percentage' => 50, 'months_ago' => 3, 'duration_days' => 21, 'status' => ProjectStatus::Completed],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Settlement, 'ratio' => 0.5, 'offset' => 21, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Domain, 'amount' => 200_000, 'offset' => 2, 'description' => 'Domain batikayu.co.id 1 tahun'],
                    ['category' => ExpenseCategory::Hosting, 'amount' => 1_400_000, 'offset' => 2, 'description' => 'VPS 2GB 1 tahun'],
                    ['category' => ExpenseCategory::License, 'amount' => 450_000, 'offset' => 5, 'description' => 'Lisensi template email transaksional'],
                ],
            ],
            [
                'client' => ['name' => 'Bambang Setiawan', 'business_name' => 'Koperasi Karyawan Sejahtera', 'whatsapp' => '081234567803', 'email' => 'sekretariat@kopkarsejahtera.or.id', 'address' => 'Jl. Pemuda No. 45, Semarang, Jawa Tengah', 'notes' => 'Butuh invoice resmi berkop untuk laporan pengurus.'],
                'project' => ['title' => 'Sistem Informasi Simpan Pinjam Koperasi', 'package' => 'sistem-aplikasi-web-custom', 'deal_price' => 18_000_000, 'dp_percentage' => 40, 'months_ago' => 4, 'duration_days' => 45, 'status' => ProjectStatus::Completed],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.4, 'offset' => 0, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Instalment, 'ratio' => 0.3, 'offset' => 25, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Settlement, 'ratio' => 0.3, 'offset' => 46, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Hosting, 'amount' => 2_400_000, 'offset' => 3, 'description' => 'VPS 4GB 1 tahun'],
                    ['category' => ExpenseCategory::Outsource, 'amount' => 3_500_000, 'offset' => 20, 'description' => 'Jasa QA dan uji coba sistem'],
                    ['category' => ExpenseCategory::License, 'amount' => 900_000, 'offset' => 10, 'description' => 'Lisensi komponen laporan PDF'],
                ],
            ],
            [
                'client' => ['name' => 'Dewi Anggraini', 'business_name' => 'Warung Pintar Grup', 'whatsapp' => '081234567804', 'email' => 'dewi@warungpintar.id', 'address' => 'Jl. Raya Darmo No. 120, Surabaya, Jawa Timur', 'notes' => 'Koordinasi lewat grup WhatsApp bersama tim IT internal.'],
                'project' => ['title' => 'Aplikasi Kasir Android Multi Cabang', 'package' => 'aplikasi-mobile-android-ios', 'deal_price' => 27_500_000, 'dp_percentage' => 40, 'months_ago' => 6, 'duration_days' => 70, 'status' => ProjectStatus::Completed],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.4, 'offset' => 0, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Instalment, 'ratio' => 0.3, 'offset' => 35, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Settlement, 'ratio' => 0.3, 'offset' => 72, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::License, 'amount' => 1_600_000, 'offset' => 4, 'description' => 'Akun developer Play Store dan App Store'],
                    ['category' => ExpenseCategory::Outsource, 'amount' => 6_000_000, 'offset' => 30, 'description' => 'Desainer UI/UX freelance'],
                    ['category' => ExpenseCategory::Hosting, 'amount' => 3_600_000, 'offset' => 5, 'description' => 'VPS backend + object storage 1 tahun'],
                ],
            ],
            [
                'client' => ['name' => 'Ahmad Fauzi', 'business_name' => 'Yayasan Cahaya Harapan', 'whatsapp' => '081234567805', 'email' => 'sekretariat@cahayaharapan.org', 'address' => 'Jl. Cihampelas No. 210, Bandung, Jawa Barat', 'notes' => 'Pembayaran menunggu pencairan dari bendahara, biasanya mundur beberapa hari.'],
                'project' => ['title' => 'Website Yayasan Cahaya Harapan', 'package' => 'company-profile', 'deal_price' => 3_200_000, 'dp_percentage' => 50, 'months_ago' => 7, 'duration_days' => 16, 'status' => ProjectStatus::Completed],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Qris, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Settlement, 'ratio' => 0.5, 'offset' => 18, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Domain, 'amount' => 350_000, 'offset' => 1, 'description' => 'Domain cahayaharapan.org 1 tahun'],
                    ['category' => ExpenseCategory::Hosting, 'amount' => 600_000, 'offset' => 1, 'description' => 'Shared hosting 1 tahun'],
                ],
            ],
            [
                'client' => ['name' => 'Siti Marlina', 'business_name' => 'Laundry Kilat', 'whatsapp' => '081234567806', 'email' => 'sitimarlina@laundrykilat.id', 'address' => 'Jl. Soekarno Hatta No. 77, Malang, Jawa Timur', 'notes' => 'Ambil paket maintenance Pro sejak bulan kedua.'],
                'project' => ['title' => 'Sistem Manajemen Laundry Kilat', 'package' => 'sistem-aplikasi-web-custom', 'deal_price' => 11_000_000, 'dp_percentage' => 50, 'months_ago' => 9, 'duration_days' => 30, 'status' => ProjectStatus::Completed],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Settlement, 'ratio' => 0.5, 'offset' => 32, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Hosting, 'amount' => 1_200_000, 'offset' => 2, 'description' => 'VPS 2GB 1 tahun'],
                    ['category' => ExpenseCategory::License, 'amount' => 750_000, 'offset' => 6, 'description' => 'Kuota API WhatsApp Gateway'],
                ],
            ],
            [
                'client' => ['name' => 'Yoga Pratama', 'business_name' => 'Bengkel Motor Jaya', 'whatsapp' => '081234567807', 'email' => 'yoga@bengkeljaya.id', 'address' => 'Jl. Ahmad Yani No. 19, Bekasi, Jawa Barat', 'notes' => 'Baru pertama kali punya website, perlu dipandu pelan-pelan.'],
                'project' => ['title' => 'Landing Page Bengkel Motor Jaya', 'package' => 'landing-page', 'deal_price' => 1_500_000, 'dp_percentage' => 50, 'months_ago' => 0, 'duration_days' => 7, 'status' => ProjectStatus::InProgress],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Qris, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Domain, 'amount' => 180_000, 'offset' => 1, 'description' => 'Domain bengkeljaya.id 1 tahun'],
                ],
            ],
            [
                'client' => ['name' => 'Maria Simanjuntak', 'business_name' => 'Klinik Sehat Bersama', 'whatsapp' => '081234567808', 'email' => 'admin@kliniksehatbersama.id', 'address' => 'Jl. Gatot Subroto No. 5, Medan, Sumatera Utara', 'notes' => 'Butuh fitur reservasi dokter di tahap berikutnya.'],
                'project' => ['title' => 'Website Klinik Sehat Bersama', 'package' => 'company-profile', 'deal_price' => 4_200_000, 'dp_percentage' => 50, 'months_ago' => 0, 'duration_days' => 14, 'status' => ProjectStatus::Revision],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                    ['type' => PaymentType::Settlement, 'ratio' => 0.5, 'offset' => 12, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Pending],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Domain, 'amount' => 190_000, 'offset' => 2, 'description' => 'Domain kliniksehatbersama.id 1 tahun'],
                    ['category' => ExpenseCategory::Hosting, 'amount' => 650_000, 'offset' => 2, 'description' => 'Shared hosting 1 tahun'],
                ],
            ],
            [
                'client' => ['name' => 'Putu Ardana', 'business_name' => 'Bali Surf Camp', 'whatsapp' => '081234567809', 'email' => 'putu@balisurfcamp.com', 'address' => 'Jl. Pantai Berawa No. 33, Badung, Bali', 'notes' => 'Konten dua bahasa, Indonesia dan Inggris.'],
                'project' => ['title' => 'Toko Online Paket Surfing Bali', 'package' => 'toko-online', 'deal_price' => 9_500_000, 'dp_percentage' => 50, 'months_ago' => 1, 'duration_days' => 25, 'status' => ProjectStatus::AwaitingSettlement],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Transfer, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Domain, 'amount' => 250_000, 'offset' => 3, 'description' => 'Domain balisurfcamp.com 1 tahun'],
                    ['category' => ExpenseCategory::Hosting, 'amount' => 1_450_000, 'offset' => 3, 'description' => 'VPS 2GB 1 tahun'],
                    ['category' => ExpenseCategory::Outsource, 'amount' => 1_200_000, 'offset' => 12, 'description' => 'Penerjemah konten Inggris'],
                ],
            ],
            [
                'client' => ['name' => 'Fitriani Nurhaliza', 'business_name' => 'Hijab Story', 'whatsapp' => '081234567810', 'email' => 'fitri@hijabstory.id', 'address' => 'Jl. Margonda Raya No. 300, Depok, Jawa Barat', 'notes' => 'Baru deal, materi produk belum dikirim.'],
                'project' => ['title' => 'Landing Page Reseller Hijab Story', 'package' => 'landing-page', 'deal_price' => 1_800_000, 'dp_percentage' => 50, 'months_ago' => 0, 'duration_days' => 7, 'status' => ProjectStatus::Queued],
                'payments' => [
                    ['type' => PaymentType::DownPayment, 'ratio' => 0.5, 'offset' => 0, 'method' => PaymentMethod::Qris, 'status' => PaymentStatus::Pending],
                ],
                'expenses' => [],
            ],
            [
                'client' => ['name' => 'Rizky Ramadhan', 'business_name' => 'Sablon Kaos Nusantara', 'whatsapp' => '081234567811', 'email' => 'rizky@sablonnusantara.id', 'address' => 'Jl. Diponegoro No. 14, Solo, Jawa Tengah', 'notes' => 'Perpanjangan maintenance tiap awal bulan.'],
                'project' => ['title' => 'Maintenance Bulanan Sablon Nusantara', 'package' => 'maintenance-pro', 'deal_price' => 650_000, 'dp_percentage' => 100, 'months_ago' => 1, 'duration_days' => 30, 'status' => ProjectStatus::Completed],
                'payments' => [
                    ['type' => PaymentType::Maintenance, 'ratio' => 1.0, 'offset' => 0, 'method' => PaymentMethod::Qris, 'status' => PaymentStatus::Verified],
                ],
                'expenses' => [
                    ['category' => ExpenseCategory::Other, 'amount' => 120_000, 'offset' => 2, 'description' => 'Kuota monitoring uptime'],
                ],
            ],
        ];

        foreach ($blueprints as $blueprint) {
            $client = Client::query()->updateOrCreate(
                ['whatsapp' => $blueprint['client']['whatsapp']],
                $blueprint['client'],
            );

            $meta = $blueprint['project'];
            $startDate = $today->copy()->subMonthsNoOverflow($meta['months_ago'])->startOfMonth()->addDays(4);
            $deadline = $startDate->copy()->addDays($meta['duration_days']);

            $project = Project::query()->updateOrCreate(
                ['title' => $meta['title']],
                [
                    'client_id' => $client->id,
                    'package_id' => $packages[$meta['package']] ?? null,
                    'deal_price' => $meta['deal_price'],
                    'dp_percentage' => $meta['dp_percentage'],
                    'start_date' => $startDate,
                    'deadline' => $deadline,
                    'status' => $meta['status'],
                    'notes' => $blueprint['client']['notes'],
                ],
            );

            $project->payments()->delete();
            $project->expenses()->delete();

            foreach ($blueprint['payments'] as $payment) {
                $isTransfer = $payment['method'] === PaymentMethod::Transfer;
                $isVerified = $payment['status'] === PaymentStatus::Verified;
                $paidAt = $startDate->copy()->addDays($payment['offset']);

                $project->payments()->create([
                    'type' => $payment['type'],
                    'amount' => (int) round($meta['deal_price'] * $payment['ratio']),
                    'method' => $payment['method'],
                    'bank_account_id' => $isTransfer ? ($payment['offset'] > 20 ? $mandiri?->id : $bca?->id) : null,
                    'status' => $payment['status'],
                    'paid_at' => $paidAt,
                    'verified_at' => $isVerified ? $paidAt->copy()->addHours(3) : null,
                    'note' => $isVerified ? null : 'Menunggu pengecekan mutasi rekening.',
                ]);
            }

            foreach ($blueprint['expenses'] as $expense) {
                $project->expenses()->create([
                    'category' => $expense['category'],
                    'amount' => $expense['amount'],
                    'expense_date' => $startDate->copy()->addDays($expense['offset']),
                    'description' => $expense['description'],
                ]);
            }
        }
    }

    private function seedInquiries(Carbon $today): void
    {
        $inquiries = [
            ['name' => 'Agus Salim', 'business_name' => 'Toko Bangunan Berkah', 'whatsapp' => '081298765401', 'email' => 'agus@tokoberkah.id', 'service_type' => 'Company Profile', 'budget_range' => 'Rp 3 juta - Rp 5 juta', 'deadline' => '1 bulan', 'description' => 'Saya punya toko bangunan di Bogor. Ingin punya website supaya pelanggan bisa lihat daftar barang dan harga tanpa perlu datang ke toko dulu.', 'status' => InquiryStatus::New, 'days_ago' => 1],
            ['name' => 'Nadia Rahmawati', 'business_name' => 'Nadia Cake & Bakery', 'whatsapp' => '081298765402', 'email' => null, 'service_type' => 'Landing Page', 'budget_range' => 'Di bawah Rp 3 juta', 'deadline' => 'Secepatnya', 'description' => 'Butuh halaman untuk pre-order kue lebaran. Isinya katalog kue, harga, dan tombol pesan langsung ke WhatsApp.', 'status' => InquiryStatus::New, 'days_ago' => 2],
            ['name' => 'Deni Kurniawan', 'business_name' => 'CV Mitra Logistik', 'whatsapp' => '081298765403', 'email' => 'deni@mitralogistik.co.id', 'service_type' => 'Sistem / Aplikasi Web Custom', 'budget_range' => 'Rp 10 juta - Rp 25 juta', 'deadline' => '2-3 bulan', 'description' => 'Kami butuh sistem untuk mencatat pengiriman barang, status armada, dan surat jalan digital. Saat ini masih pakai Excel dan sering bentrok antar admin.', 'status' => InquiryStatus::Contacted, 'days_ago' => 5],
            ['name' => 'Laila Hasanah', 'business_name' => 'Butik Laila', 'whatsapp' => '081298765404', 'email' => 'laila@butiklaila.id', 'service_type' => 'Toko Online', 'budget_range' => 'Rp 5 juta - Rp 10 juta', 'deadline' => '1 bulan', 'description' => 'Ingin pindah dari marketplace ke toko sendiri. Produk sekitar 150 item, butuh pembayaran otomatis dan hitung ongkir.', 'status' => InquiryStatus::Contacted, 'days_ago' => 8],
            ['name' => 'Fitriani Nurhaliza', 'business_name' => 'Hijab Story', 'whatsapp' => '081234567810', 'email' => 'fitri@hijabstory.id', 'service_type' => 'Landing Page', 'budget_range' => 'Di bawah Rp 3 juta', 'deadline' => 'Secepatnya', 'description' => 'Butuh landing page untuk rekrut reseller hijab. Ada form pendaftaran reseller dan penjelasan sistem keuntungannya.', 'status' => InquiryStatus::Deal, 'days_ago' => 12],
            ['name' => 'Bagus Prakoso', 'business_name' => 'Futsal Arena 88', 'whatsapp' => '081298765405', 'email' => null, 'service_type' => 'Aplikasi Mobile Android & iOS', 'budget_range' => 'Rp 10 juta - Rp 25 juta', 'deadline' => 'Fleksibel', 'description' => 'Mau bikin aplikasi booking lapangan futsal. Setelah diskusi internal, kami tunda dulu ke tahun depan karena anggaran belum turun.', 'status' => InquiryStatus::Cancelled, 'days_ago' => 20],
            ['name' => 'Sri Wahyuni', 'business_name' => 'PAUD Tunas Bangsa', 'whatsapp' => '081298765406', 'email' => 'paudtunasbangsa@gmail.com', 'service_type' => 'Company Profile', 'budget_range' => 'Di bawah Rp 3 juta', 'deadline' => 'Fleksibel', 'description' => 'Kami sekolah PAUD kecil, ingin punya website sederhana berisi profil, kegiatan anak, dan cara pendaftaran. Anggaran terbatas, mohon info paket paling hemat.', 'status' => InquiryStatus::New, 'days_ago' => 3],
        ];

        foreach ($inquiries as $inquiry) {
            $daysAgo = $inquiry['days_ago'];
            unset($inquiry['days_ago']);

            $createdAt = $today->copy()->subDays($daysAgo)->setTime(9, 30);

            Inquiry::query()->updateOrCreate(
                ['whatsapp' => $inquiry['whatsapp'], 'service_type' => $inquiry['service_type']],
                $inquiry + ['created_at' => $createdAt, 'updated_at' => $createdAt],
            );
        }
    }
}
