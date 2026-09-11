<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Packages\PackageResource;
use App\Filament\Resources\Portfolios\PortfolioResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Portfolio;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->admin = User::query()->firstOrFail();
    }

    public function test_halaman_login_admin_bisa_dibuka(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_panel_admin_menolak_tamu(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_daftar_resource_konten_bisa_dibuka(): void
    {
        $pages = [
            PackageResource::getUrl('index'),
            PortfolioResource::getUrl('index'),
            TestimonialResource::getUrl('index'),
            FaqResource::getUrl('index'),
            ManageSettings::getUrl(),
        ];

        foreach ($pages as $url) {
            $this->actingAs($this->admin)->get($url)->assertOk();
        }
    }

    public function test_form_create_dan_edit_resource_konten_bisa_dibuka(): void
    {
        $urls = [
            PackageResource::getUrl('create'),
            PackageResource::getUrl('edit', ['record' => Package::query()->firstOrFail()]),
            PortfolioResource::getUrl('create'),
            PortfolioResource::getUrl('edit', ['record' => Portfolio::query()->firstOrFail()]),
            TestimonialResource::getUrl('create'),
            TestimonialResource::getUrl('edit', ['record' => Testimonial::query()->firstOrFail()]),
            FaqResource::getUrl('create'),
            FaqResource::getUrl('edit', ['record' => Faq::query()->firstOrFail()]),
        ];

        foreach ($urls as $url) {
            $this->actingAs($this->admin)->get($url)->assertOk();
        }
    }

    public function test_seeder_mengisi_seluruh_tabel_konten(): void
    {
        $this->assertSame(8, Package::count());
        $this->assertSame(8, Portfolio::count());
        $this->assertSame(24, Portfolio::query()->withCount('images')->get()->sum('images_count'));
        $this->assertSame(6, Testimonial::count());
        $this->assertSame(10, Faq::count());
        $this->assertNotEmpty(Setting::get('hero_title'));
    }

    public function test_nominal_uang_tersimpan_sebagai_integer(): void
    {
        $package = Package::query()->where('slug', 'landing-page')->firstOrFail();

        $this->assertIsInt($package->price);
        $this->assertSame(1_500_000, $package->price);
        $this->assertSame('Rp 1.500.000', rupiah($package->price));
        $this->assertSame('mulai dari Rp 6.500.000', Package::query()->where('slug', 'toko-online')->firstOrFail()->price_label);
    }
}
