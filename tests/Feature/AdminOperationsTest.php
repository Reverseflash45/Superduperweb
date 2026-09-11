<?php

namespace Tests\Feature;

use App\Enums\InquiryStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProjectStatus;
use App\Filament\Resources\BankAccounts\BankAccountResource;
use App\Filament\Resources\Clients\ClientResource;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Filament\Resources\Payments\PaymentResource;
use App\Filament\Resources\ProjectExpenses\ProjectExpenseResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Widgets\FinanceOverview;
use App\Filament\Widgets\RevenueMarginChart;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->admin = User::query()->firstOrFail();
        $this->actingAs($this->admin);
    }

    public function test_halaman_operasional_dan_keuangan_bisa_dibuka(): void
    {
        $urls = [
            '/admin',
            InquiryResource::getUrl('index'),
            InquiryResource::getUrl('edit', ['record' => Inquiry::query()->firstOrFail()]),
            ClientResource::getUrl('index'),
            ClientResource::getUrl('edit', ['record' => Client::query()->firstOrFail()]),
            ProjectResource::getUrl('index'),
            ProjectResource::getUrl('create'),
            ProjectResource::getUrl('edit', ['record' => Project::query()->firstOrFail()]),
            PaymentResource::getUrl('index'),
            PaymentResource::getUrl('create'),
            PaymentResource::getUrl('edit', ['record' => Payment::query()->firstOrFail()]),
            ProjectExpenseResource::getUrl('index'),
            ProjectExpenseResource::getUrl('edit', ['record' => ProjectExpense::query()->firstOrFail()]),
            BankAccountResource::getUrl('index'),
            BankAccountResource::getUrl('edit', ['record' => BankAccount::query()->firstOrFail()]),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_widget_dashboard_render(): void
    {
        Livewire::test(FinanceOverview::class)->assertOk();
        Livewire::test(RevenueMarginChart::class)->assertOk();
    }

    public function test_grafik_menampilkan_12_bulan_dengan_margin(): void
    {
        $widget = Livewire::test(RevenueMarginChart::class)->instance();
        $data = (fn (): array => $this->getCachedData())->call($widget);

        $this->assertCount(12, $data['labels']);
        $this->assertSame(['Omzet', 'Biaya', 'Margin'], array_column($data['datasets'], 'label'));

        [$revenue, $expense, $margin] = array_column($data['datasets'], 'data');

        foreach (array_keys($margin) as $i) {
            $this->assertSame($revenue[$i] - $expense[$i], $margin[$i]);
        }
    }

    public function test_omzet_hanya_menghitung_pembayaran_terverifikasi(): void
    {
        $project = Project::query()->where('title', 'like', '%Klinik Sehat Bersama%')->firstOrFail();

        $verified = (int) $project->payments()->where('status', PaymentStatus::Verified)->sum('amount');
        $pending = (int) $project->payments()->where('status', PaymentStatus::Pending)->sum('amount');

        $this->assertGreaterThan(0, $pending, 'Data dummy harus punya pembayaran yang belum diverifikasi.');
        $this->assertSame($verified, $project->total_paid);
        $this->assertSame($project->deal_price - $verified, $project->remaining);
        $this->assertSame($verified - $project->total_expense, $project->margin);
    }

    public function test_jadikan_proyek_membuat_klien_dan_proyek_dari_brief(): void
    {
        $inquiry = Inquiry::query()->where('status', InquiryStatus::Deal)->firstOrFail();

        Client::query()->where('whatsapp', $inquiry->whatsapp)->delete();

        $this->assertDatabaseMissing('clients', ['whatsapp' => $inquiry->whatsapp]);

        Livewire::test(ListInquiries::class)
            ->callAction(TestAction::make('jadikanProyek')->table($inquiry), data: [
                'package_id' => null,
                'title' => 'Landing Page Hijab Story',
                'deal_price' => 1_800_000,
                'dp_percentage' => 50,
                'start_date' => now()->toDateString(),
                'deadline' => now()->addDays(7)->toDateString(),
            ])
            ->assertHasNoActionErrors();

        $client = Client::query()->where('whatsapp', $inquiry->whatsapp)->firstOrFail();
        $project = Project::query()->where('inquiry_id', $inquiry->id)->firstOrFail();

        $this->assertSame($client->id, $project->client_id);
        $this->assertSame(1_800_000, $project->deal_price);
        $this->assertSame(ProjectStatus::Queued, $project->status);
        $this->assertSame(40, strlen($project->invoice_code));
    }

    public function test_admin_bisa_memverifikasi_dan_menolak_pembayaran(): void
    {
        $payment = Payment::query()->where('status', PaymentStatus::Pending)->firstOrFail();

        Livewire::test(ListPayments::class)
            ->callAction(TestAction::make('verifikasi')->table($payment))
            ->assertHasNoActionErrors();

        $payment->refresh();
        $this->assertSame(PaymentStatus::Verified, $payment->status);
        $this->assertNotNull($payment->verified_at);

        Livewire::test(ListPayments::class)
            ->callAction(TestAction::make('tolak')->table($payment), data: ['rejection_reason' => 'Nominal transfer tidak sesuai tagihan.'])
            ->assertHasNoActionErrors();

        $payment->refresh();
        $this->assertSame(PaymentStatus::Rejected, $payment->status);
        $this->assertSame('Nominal transfer tidak sesuai tagihan.', $payment->rejection_reason);
        $this->assertNull($payment->verified_at);
    }

    public function test_halaman_pembayaran_tetap_terbuka_saat_ada_bukti_di_disk_privat(): void
    {
        Storage::fake('private');

        $payment = Payment::query()->firstOrFail();
        Storage::disk('private')->put('bukti-pembayaran/contoh.jpg', 'isi-berkas');
        $payment->update(['proof_path' => 'bukti-pembayaran/contoh.jpg']);

        // Filament memanggil Storage::url() tanpa try/catch, jadi disk privat harus
        // sanggup membuat URL bertanda tangan agar halaman ini tidak error.
        $this->get(PaymentResource::getUrl('edit', ['record' => $payment]))->assertOk();
        $this->get(PaymentResource::getUrl('index'))->assertOk();
    }

    public function test_admin_bisa_mengunduh_bukti_pembayaran(): void
    {
        Storage::fake('private');

        $payment = Payment::query()->firstOrFail();
        Storage::disk('private')->put('bukti-pembayaran/contoh.pdf', '%PDF-1.7 contoh');
        $payment->update(['proof_path' => 'bukti-pembayaran/contoh.pdf']);

        Livewire::test(ListPayments::class)
            ->callAction(TestAction::make('unduhBukti')->table($payment))
            ->assertFileDownloaded();
    }

    public function test_kode_invoice_unik_dan_tidak_mudah_ditebak(): void
    {
        $codes = Project::query()->pluck('invoice_code');

        $this->assertSame($codes->count(), $codes->unique()->count());

        foreach ($codes as $code) {
            $this->assertMatchesRegularExpression('/^[a-z0-9]{40}$/', $code);
        }
    }
}
