<?php

namespace App\Console\Commands;

use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Pemeriksaan sebelum situs dibuka untuk klien: data contoh dari seeder,
 * sandi bawaan, dan pengaturan produksi yang terlewat.
 *
 * Gagal (kode keluar 1) bila ada hal yang bisa merugikan klien, misalnya
 * rekening contoh yang masih aktif di halaman invoice.
 */
class CekRilis extends Command
{
    protected $signature = 'superduperweb:cek-rilis';

    protected $description = 'Periksa data contoh dan pengaturan sebelum situs dibuka untuk klien';

    /** Nilai rekaan dari DemoSeeder dan SettingSeeder; dijaga sinkron oleh pengujian. */
    public const NOMOR_REKENING_CONTOH = ['8820134455', '1400012345678', '005601000123456', '7731099224'];

    public const WHATSAPP_CONTOH = '6281234567890';

    public const QRIS_CONTOH = 'pengaturan/qris-superduperweb.svg';

    public const KLIEN_CONTOH = ['rina@kopikenangansenja.id', 'hendra@batikayu.co.id', 'dewi@warungpintar.id'];

    public const TESTIMONI_CONTOH = ['Rina Kusuma', 'Hendra Wijaya', 'Bambang Setiawan', 'Dewi Anggraini', 'Ustaz Ahmad Fauzi', 'Siti Marlina'];

    /** @var list<array{0: string, 1: string}> */
    private array $gagal = [];

    /** @var list<array{0: string, 1: string}> */
    private array $peringatan = [];

    public function handle(): int
    {
        $this->periksaLingkungan();
        $this->periksaAdmin();
        $this->periksaPembayaran();
        $this->periksaKontak();
        $this->periksaDataContoh();

        foreach ($this->gagal as [$judul, $saran]) {
            $this->line("<fg=red>✗</> {$judul}");
            $this->line("  <fg=gray>{$saran}</>");
        }
        foreach ($this->peringatan as [$judul, $saran]) {
            $this->line("<fg=yellow>!</> {$judul}");
            $this->line("  <fg=gray>{$saran}</>");
        }

        if ($this->gagal === []) {
            $this->info($this->peringatan === []
                ? 'Siap dibuka untuk klien.'
                : 'Tidak ada yang menghalangi rilis, tapi periksa peringatan di atas.');

            return self::SUCCESS;
        }

        $this->error(count($this->gagal).' hal harus dibereskan sebelum situs dibuka untuk klien.');

        return self::FAILURE;
    }

    private function periksaLingkungan(): void
    {
        if (config('app.debug')) {
            $this->gagal[] = ['APP_DEBUG masih true', 'Set APP_DEBUG=false di .env supaya pesan galat tidak membocorkan isi server.'];
        }
        if (! app()->isProduction()) {
            $this->peringatan[] = ['APP_ENV bukan production', 'Set APP_ENV=production di server.'];
        }
        if (! str_starts_with((string) config('app.url'), 'https://')) {
            $this->peringatan[] = ['APP_URL belum https', 'Gambar di /storage dan tautan invoice dibangun dari APP_URL.'];
        }
        if (config('mail.default') === 'log') {
            $this->peringatan[] = ['Email notifikasi masih ke log', 'Isi MAIL_* dengan SMTP supaya brief dan bukti pembayaran baru sampai ke email admin.'];
        }
        if (! is_link(public_path('storage')) && ! is_dir(public_path('storage'))) {
            $this->gagal[] = ['public/storage belum ada', 'Jalankan php artisan storage:link (gambar portofolio, logo bank, dan QRIS ada di sana).'];
        }
    }

    private function periksaAdmin(): void
    {
        $bawaan = User::query()->where('email', 'admin@superduperweb.id')->first();
        if ($bawaan && Hash::check('password', $bawaan->password)) {
            $this->gagal[] = ['Akun admin bawaan masih memakai sandi "password"', 'Ganti sandinya di panel admin (Profil), atau hapus akun itu setelah membuat admin dengan php artisan make:filament-user.'];
        }
        if (! User::query()->exists()) {
            $this->gagal[] = ['Belum ada akun admin', 'Buat dengan php artisan make:filament-user.'];
        }
    }

    private function periksaPembayaran(): void
    {
        $contoh = BankAccount::query()->whereIn('account_number', self::NOMOR_REKENING_CONTOH)->get();
        if ($contoh->isNotEmpty()) {
            $this->gagal[] = [
                'Rekening contoh masih tersimpan: '.$contoh->pluck('bank_name')->join(', '),
                'Hapus di panel admin (Rekening Bank). Nomor contoh bisa saja milik orang lain sungguhan, dan klien bisa salah transfer.',
            ];
        }
        if (! BankAccount::query()->where('is_active', true)->exists()) {
            $this->peringatan[] = ['Belum ada rekening aktif', 'Halaman invoice hanya akan menampilkan QRIS (kalau ada).'];
        }
        $qris = (string) Setting::get('qris_image', '');
        if ($qris === self::QRIS_CONTOH) {
            $this->gagal[] = ['Gambar QRIS masih contoh', 'Unggah QRIS asli di panel admin (Pengaturan).'];
        } elseif ($qris === '') {
            $this->peringatan[] = ['Belum ada gambar QRIS', 'Unggah di panel admin (Pengaturan) kalau mau menerima QRIS.'];
        }
    }

    private function periksaKontak(): void
    {
        if (preg_replace('/\D/', '', (string) Setting::get('whatsapp_number', '')) === self::WHATSAPP_CONTOH) {
            $this->gagal[] = ['Nomor WhatsApp masih contoh (6281234567890)', 'Semua tombol konsultasi mengarah ke nomor ini. Ganti di panel admin (Pengaturan).'];
        }
        foreach (['instagram_url', 'facebook_url', 'tiktok_url'] as $kunci) {
            $url = (string) Setting::get($kunci, '');
            if ($url !== '' && str_ends_with(rtrim($url, '/'), 'superduperweb')) {
                $this->peringatan[] = ["{$kunci} masih tautan contoh", 'Pastikan akunnya memang milikmu, atau kosongkan supaya ikonnya tidak tampil.'];
            }
        }
    }

    private function periksaDataContoh(): void
    {
        if (Testimonial::query()->whereIn('name', self::TESTIMONI_CONTOH)->exists()) {
            $this->gagal[] = ['Testimoni rekaan dari DemoSeeder masih ada', 'Hapus di panel admin (Testimoni). Testimoni palsu menyesatkan calon klien.'];
        }
        if (Client::query()->whereIn('email', self::KLIEN_CONTOH)->exists()) {
            $this->gagal[] = ['Klien dan proyek rekaan dari DemoSeeder masih ada', 'Data ini ikut menghitung statistik "proyek selesai" di halaman depan. Hapus klien contoh di panel admin.'];
        }
    }
}
