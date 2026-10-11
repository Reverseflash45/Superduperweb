<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Data rekaan untuk mencoba panel admin di lokal: rekening bank, QRIS,
 * testimoni, klien, brief, proyek, pembayaran, dan biaya. Semuanya fiktif.
 *
 * Jangan dijalankan di produksi. Testimoni dan angka statistik di situs
 * publik harus berasal dari klien sungguhan.
 *
 *     php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->error('DemoSeeder berisi data rekaan dan tidak boleh dijalankan di produksi.');

            return;
        }

        // Rekening dan QRIS contoh dibutuhkan halaman invoice dan data operasional.
        $this->call(BankAccountSeeder::class);
        Setting::put('qris_image', DummyImage::qris('pengaturan/qris-superduperweb.svg'));

        $this->call([
            TestimonialSeeder::class,
            OperationalSeeder::class,
        ]);
    }
}
