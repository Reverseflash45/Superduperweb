<?php

namespace App\Support;

use App\Enums\ProjectStatus;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Pembungkus pengaturan situs untuk dipakai di seluruh halaman publik,
 * supaya view tidak perlu tahu bentuk penyimpanan tabel settings.
 */
class SiteSettings
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }

    public function name(): string
    {
        return (string) $this->get('site_name', config('app.name'));
    }

    public function whatsappNumber(): string
    {
        return wa_number($this->get('whatsapp_number', ''));
    }

    public function email(): ?string
    {
        return $this->get('business_email');
    }

    public function metaDescription(): string
    {
        return (string) $this->get(
            'meta_description',
            'Jasa pembuatan website dan aplikasi untuk UMKM serta organisasi di Indonesia.',
        );
    }

    public function googleAnalyticsId(): ?string
    {
        return $this->get('google_analytics_id');
    }

    public function qrisUrl(): ?string
    {
        $path = $this->get('qris_image');

        return $path ? Storage::disk('public')->url($path) : null;
    }

    /** Link sosial media yang kosong tidak ditampilkan di footer. */
    public function socialLinks(): Collection
    {
        return collect([
            ['key' => 'instagram', 'label' => 'Instagram', 'url' => $this->get('instagram_url')],
            ['key' => 'facebook', 'label' => 'Facebook', 'url' => $this->get('facebook_url')],
            ['key' => 'tiktok', 'label' => 'TikTok', 'url' => $this->get('tiktok_url')],
        ])->filter(fn (array $link) => filled($link['url']))->values();
    }

    public function activeBankAccounts(): Collection
    {
        return BankAccount::query()->active()->ordered()->get();
    }

    /**
     * Statistik dihitung langsung dari database, bukan angka yang diketik manual.
     *
     * @return array{projects: int, clients: int, visible: bool, minimum: int}
     */
    public function statistics(): array
    {
        $completed = Project::query()->where('status', ProjectStatus::Completed)->count();
        $minimum = (int) $this->get('min_completed_projects_for_stats', 3);

        return [
            'projects' => $completed,
            'clients' => Client::query()->whereHas('projects')->count(),
            'minimum' => $minimum,
            'visible' => $completed >= $minimum,
        ];
    }
}
