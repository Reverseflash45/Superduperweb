<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Data rekaan untuk mencoba panel admin di lokal: testimoni, klien, brief,
 * proyek, pembayaran, dan biaya. Semuanya fiktif.
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
        $this->call([
            TestimonialSeeder::class,
            OperationalSeeder::class,
        ]);
    }
}
