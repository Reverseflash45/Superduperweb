<?php

namespace Tests\Feature;

use App\Console\Commands\CekRilis;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Setting;
use App\Models\Testimonial;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CekRilisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_data_demo_terdeteksi_dan_menggagalkan_rilis(): void
    {
        $this->seed([DatabaseSeeder::class, DemoSeeder::class]);

        $this->artisan('superduperweb:cek-rilis')
            ->expectsOutputToContain('Rekening contoh masih tersimpan')
            ->expectsOutputToContain('Gambar QRIS masih contoh')
            ->expectsOutputToContain('Testimoni rekaan')
            ->expectsOutputToContain('Klien dan proyek rekaan')
            ->expectsOutputToContain('sandi "password"')
            ->assertExitCode(1);
    }

    public function test_daftar_data_contoh_sinkron_dengan_seeder(): void
    {
        $this->seed([DatabaseSeeder::class, DemoSeeder::class]);

        $this->assertEqualsCanonicalizing(CekRilis::NOMOR_REKENING_CONTOH, BankAccount::query()->pluck('account_number')->all());
        $this->assertEqualsCanonicalizing(CekRilis::TESTIMONI_CONTOH, Testimonial::query()->pluck('name')->all());
        $this->assertSame(CekRilis::QRIS_CONTOH, Setting::get('qris_image'));
        $this->assertSame(CekRilis::WHATSAPP_CONTOH, preg_replace('/\D/', '', (string) Setting::get('whatsapp_number')));
        $this->assertSame(count(CekRilis::KLIEN_CONTOH), Client::query()->whereIn('email', CekRilis::KLIEN_CONTOH)->count());
    }

    public function test_seeder_utama_tidak_mengisi_rekening_dan_qris(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(0, BankAccount::query()->count());
        $this->assertSame('', (string) Setting::get('qris_image', ''));
        $this->assertSame(0, Testimonial::query()->count());
    }
}
