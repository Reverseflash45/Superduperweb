<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin dengan sandi "password" hanya untuk lokal. Di produksi buat
        // admin sendiri: php artisan make:filament-user
        if (! app()->isProduction()) {
            User::query()->updateOrCreate(
                ['email' => 'admin@superduperweb.id'],
                [
                    'name' => 'Admin SuperDuperWeb',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );
        }

        // Rekening bank dan QRIS sengaja tidak diisi: nomor rekening contoh bisa
        // saja milik orang lain sungguhan. Isi lewat panel admin (Rekening Bank,
        // Pengaturan) sebelum situs dibuka untuk klien.
        $this->call([
            SettingSeeder::class,
            PackageSeeder::class,
            PortfolioSeeder::class,
            FaqSeeder::class,
        ]);

        // Testimoni, rekening, QRIS, dan data operasional rekaan hanya untuk lokal/pengujian:
        // php artisan db:seed --class=DemoSeeder
    }
}
