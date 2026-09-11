<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\ProjectStatus;
use App\Mail\NewInquiryMail;
use App\Mail\NewPaymentProofMail;
use App\Models\BankAccount;
use App\Models\Inquiry;
use App\Models\Package;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Testimonial;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_semua_halaman_publik_bisa_dibuka(): void
    {
        $project = Project::query()->firstOrFail();
        $portfolio = Portfolio::query()->firstOrFail();

        $this->get('/')->assertOk();
        $this->get('/portofolio')->assertOk();
        $this->get('/portofolio?kategori=mobile')->assertOk();
        $this->get("/portofolio/{$portfolio->slug}")->assertOk();
        $this->get('/konsultasi')->assertOk();
        $this->get('/syarat-ketentuan')->assertOk();
        $this->get("/invoice/{$project->invoice_code}")->assertOk();
    }

    public function test_landing_page_menampilkan_data_dari_database(): void
    {
        $response = $this->get('/');

        $response->assertSee(Package::query()->where('slug', 'company-profile')->value('name'));
        $response->assertSee('Rp 1.500.000');
        $response->assertSee('mulai dari');
        $response->assertSee('Paling Populer');
        $response->assertSee(Portfolio::query()->ordered()->first()->title);
        $response->assertSee(Testimonial::query()->ordered()->first()->name);
        $response->assertSee('Berapa lama proses pembuatan website?');
        $response->assertSee('Cara Kerja');
    }

    public function test_section_statistik_mengikuti_ambang_minimum_dari_admin(): void
    {
        $completed = Project::query()->where('status', ProjectStatus::Completed)->count();

        Setting::put('min_completed_projects_for_stats', (string) ($completed + 1));
        $this->get('/')->assertDontSee('Proyek selesai');

        Setting::put('min_completed_projects_for_stats', (string) $completed);
        $this->get('/')->assertSee('Proyek selesai');
    }

    public function test_section_testimoni_hilang_kalau_belum_ada_data(): void
    {
        $this->get('/')->assertSee('Kata mereka yang sudah bekerja sama');

        Testimonial::query()->update(['is_published' => false]);

        $this->get('/')->assertDontSee('Kata mereka yang sudah bekerja sama');
    }

    public function test_link_sosial_media_kosong_tidak_ditampilkan(): void
    {
        $this->get('/')->assertSee('https://instagram.com/superduperweb');

        Setting::put('instagram_url', '');

        $this->get('/')->assertDontSee('https://instagram.com/superduperweb');
    }

    public function test_landing_page_tidak_membocorkan_nomor_rekening(): void
    {
        $response = $this->get('/');

        foreach (BankAccount::query()->pluck('account_number') as $number) {
            $response->assertDontSee($number);
        }
    }

    public function test_tombol_paket_membuka_whatsapp_dengan_nama_paket(): void
    {
        $package = Package::query()->where('slug', 'toko-online')->firstOrFail();

        $this->get('/')->assertSee(
            e(wa_link("Halo SuperDuperWeb, saya tertarik dengan paket {$package->name}. Boleh minta penjelasan lebih lanjut?")),
            false,
        );
    }

    public function test_brief_tersimpan_lalu_diarahkan_ke_whatsapp(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Agus Salim',
            'business_name' => 'Toko Bangunan Berkah',
            'whatsapp' => '081298765499',
            'email' => 'agus@contoh.id',
            'service_type' => 'Company Profile',
            'budget_range' => 'Rp 3 juta - Rp 5 juta',
            'deadline' => 'Dalam 1 bulan',
            'description' => 'Saya punya toko bangunan di Bogor dan ingin punya website katalog harga.',
            'rendered_at' => time() - 30,
        ];

        $response = $this->post('/konsultasi', $payload);

        $response->assertRedirect(route('consultation.create'));
        $response->assertSessionHas('inquiry_sent');

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', session('inquiry_sent'));
        $this->assertStringContainsString('Toko%20Bangunan%20Berkah', session('inquiry_sent'));

        $inquiry = Inquiry::query()->where('whatsapp', '081298765499')->firstOrFail();
        $this->assertSame('baru', $inquiry->status->value);

        Mail::assertSent(NewInquiryMail::class);
    }

    public function test_honeypot_menolak_kiriman_bot(): void
    {
        $this->post('/konsultasi', [
            'name' => 'Bot',
            'whatsapp' => '081200000000',
            'service_type' => 'Landing Page',
            'description' => 'Ini kiriman otomatis dari bot yang mengisi semua kolom.',
            'website' => 'https://spam.example.com',
            'rendered_at' => time() - 30,
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseMissing('inquiries', ['whatsapp' => '081200000000']);
    }

    public function test_kiriman_terlalu_cepat_ditolak(): void
    {
        $this->post('/konsultasi', [
            'name' => 'Terlalu Cepat',
            'whatsapp' => '081211111111',
            'service_type' => 'Landing Page',
            'description' => 'Form ini dikirim kurang dari tiga detik setelah dibuka.',
            'rendered_at' => time(),
        ])->assertSessionHasErrors('description');

        $this->assertDatabaseMissing('inquiries', ['whatsapp' => '081211111111']);
    }

    public function test_rate_limit_form_konsultasi_aktif(): void
    {
        $payload = fn (int $i) => [
            'name' => "Pengirim {$i}",
            'whatsapp' => '08120000'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
            'service_type' => 'Landing Page',
            'description' => 'Kebutuhan saya adalah membuat landing page sederhana untuk usaha kecil.',
            'rendered_at' => time() - 30,
        ];

        for ($i = 1; $i <= 5; $i++) {
            $this->post('/konsultasi', $payload($i))->assertRedirect();
        }

        $this->post('/konsultasi', $payload(6))->assertStatus(429);
    }

    public function test_halaman_invoice_tidak_diindeks_dan_tidak_masuk_sitemap(): void
    {
        $project = Project::query()->firstOrFail();

        $this->get("/invoice/{$project->invoice_code}")
            ->assertOk()
            ->assertSee('noindex, nofollow', false);

        $sitemap = $this->get('/sitemap.xml');
        $sitemap->assertOk();
        $sitemap->assertHeader('Content-Type', 'application/xml');
        $sitemap->assertDontSee('/invoice/');
        $sitemap->assertDontSee($project->invoice_code);
        $sitemap->assertSee(route('portfolio.index'), false);
    }

    public function test_kode_invoice_yang_salah_menghasilkan_404(): void
    {
        $this->get('/invoice/'.str_repeat('z', 40))->assertNotFound();
    }

    public function test_invoice_menampilkan_rincian_tagihan_dan_rekening(): void
    {
        $project = Project::query()->where('status', ProjectStatus::Revision)->firstOrFail();

        $response = $this->get("/invoice/{$project->invoice_code}");

        $response->assertSee(rupiah($project->deal_price));
        $response->assertSee(rupiah($project->remaining));
        $response->assertSee($project->client->name);
        $response->assertSee('Menunggu Verifikasi');

        // Nomor rekening hanya boleh muncul di halaman invoice.
        foreach (BankAccount::query()->active()->pluck('account_number') as $number) {
            $response->assertSee($number);
        }

        // Rekening nonaktif tidak ditawarkan ke klien.
        $response->assertDontSee(BankAccount::query()->where('is_active', false)->value('account_number'));
    }

    public function test_unduh_invoice_pdf(): void
    {
        $project = Project::query()->firstOrFail();

        $response = $this->get("/invoice/{$project->invoice_code}/pdf");

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_klien_bisa_mengunggah_bukti_pembayaran_ke_disk_privat(): void
    {
        Mail::fake();
        Storage::fake('private');

        $project = Project::query()->where('status', ProjectStatus::Revision)->firstOrFail();
        $bank = BankAccount::query()->active()->firstOrFail();
        $before = $project->payments()->count();

        $response = $this->post("/invoice/{$project->invoice_code}/bukti-pembayaran", [
            'amount' => '1.500.000',
            'method' => 'transfer',
            'bank_account_id' => $bank->id,
            'paid_at' => now()->toDateString(),
            'note' => 'Sudah transfer pagi ini.',
            'proof' => UploadedFile::fake()->image('bukti.jpg', 800, 600),
        ]);

        $response->assertRedirect(route('invoice.show', $project->invoice_code));
        $response->assertSessionHas('proof_uploaded');

        $payment = $project->payments()->latest('id')->firstOrFail();

        $this->assertSame($before + 1, $project->payments()->count());
        $this->assertSame(1_500_000, $payment->amount, 'Nominal harus tersimpan sebagai integer rupiah.');
        $this->assertSame(PaymentStatus::Pending, $payment->status);
        $this->assertSame(PaymentType::Instalment, $payment->type);
        $this->assertSame($bank->id, $payment->bank_account_id);

        Storage::disk('private')->assertExists($payment->proof_path);
        $this->assertStringStartsWith('bukti-pembayaran/', $payment->proof_path);

        Mail::assertSent(NewPaymentProofMail::class);
    }

    public function test_bukti_pembayaran_menolak_berkas_tidak_sesuai(): void
    {
        Storage::fake('private');

        $project = Project::query()->where('status', ProjectStatus::Revision)->firstOrFail();

        $this->post("/invoice/{$project->invoice_code}/bukti-pembayaran", [
            'amount' => '500000',
            'method' => 'qris',
            'paid_at' => now()->toDateString(),
            'proof' => UploadedFile::fake()->create('virus.exe', 100),
        ])->assertSessionHasErrors('proof');

        $this->post("/invoice/{$project->invoice_code}/bukti-pembayaran", [
            'amount' => '500000',
            'method' => 'qris',
            'paid_at' => now()->toDateString(),
            'proof' => UploadedFile::fake()->create('besar.pdf', 3000),
        ])->assertSessionHasErrors('proof');
    }

    public function test_bukti_pembayaran_tidak_bisa_diakses_lewat_url_publik(): void
    {
        $project = Project::query()->where('status', ProjectStatus::Revision)->firstOrFail();

        $this->post("/invoice/{$project->invoice_code}/bukti-pembayaran", [
            'amount' => '500000',
            'method' => 'qris',
            'paid_at' => now()->toDateString(),
            'proof' => UploadedFile::fake()->image('bukti.png'),
        ]);

        $payment = $project->payments()->latest('id')->firstOrFail();

        // Berkas tidak pernah mendarat di document root.
        $this->assertFileDoesNotExist(public_path('storage/'.$payment->proof_path));
        $this->assertFileExists(Storage::disk('private')->path($payment->proof_path));

        // Tanpa tanda tangan yang sah, rute penyajian menolak permintaan.
        $this->get('/storage/'.$payment->proof_path)->assertForbidden();
        $this->put('/storage/'.$payment->proof_path.'?upload=true', [])->assertForbidden();
    }

    public function test_filter_kategori_portofolio(): void
    {
        $mobile = Portfolio::query()->published()->where('category', 'mobile')->pluck('title');
        $web = Portfolio::query()->published()->where('category', 'web')->pluck('title');

        $response = $this->get('/portofolio?kategori=mobile');

        foreach ($mobile as $title) {
            $response->assertSee($title);
        }

        foreach ($web as $title) {
            $response->assertDontSee($title);
        }
    }

    public function test_halaman_syarat_ketentuan_menampilkan_konten_dari_admin(): void
    {
        $this->get('/syarat-ketentuan')
            ->assertOk()
            ->assertSee('Kepemilikan Source Code')
            ->assertSee('Garansi Bug');
    }

    /**
     * Regresi: setting() dipanggil puluhan kali per halaman (tiap tombol WhatsApp,
     * link sosial media, teks hero). Tanpa memo per-request, setiap panggilan
     * menembak cache store dan pada driver database jadi satu query MySQL.
     */
    public function test_halaman_publik_tidak_membanjiri_database_dengan_query(): void
    {
        $project = Project::query()->firstOrFail();
        $portfolio = Portfolio::query()->firstOrFail();

        $batas = [
            '/' => 15,
            '/portofolio' => 12,
            "/portofolio/{$portfolio->slug}" => 12,
            '/konsultasi' => 10,
            '/syarat-ketentuan' => 10,
            "/invoice/{$project->invoice_code}" => 18,
        ];

        // Listener didaftarkan sekali saja; mendaftarkannya di dalam loop membuat
        // setiap query terhitung berkali-kali.
        $penghitung = new class
        {
            public int $jumlah = 0;
        };

        DB::listen(function () use ($penghitung) {
            $penghitung->jumlah++;
        });

        foreach ($batas as $url => $maksimal) {
            $penghitung->jumlah = 0;

            $this->get($url)->assertOk();

            $this->assertLessThanOrEqual(
                $maksimal,
                $penghitung->jumlah,
                "Halaman {$url} menjalankan {$penghitung->jumlah} query, melebihi batas wajar {$maksimal}.",
            );
        }
    }

    public function test_google_analytics_hanya_dimuat_kalau_id_diisi(): void
    {
        $this->get('/')->assertDontSee('googletagmanager.com');

        Setting::put('google_analytics_id', 'G-TEST12345');

        $this->get('/')->assertSee('googletagmanager.com/gtag/js?id=G-TEST12345', false);
    }
}
