<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@superduperweb.id'],
            [
                'name' => 'Admin SuperDuperWeb',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $this->call([
            SettingSeeder::class,
            BankAccountSeeder::class,
            PackageSeeder::class,
            PortfolioSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            OperationalSeeder::class,
        ]);
    }
}
