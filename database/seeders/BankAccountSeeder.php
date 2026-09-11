<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['bank_name' => 'BCA', 'account_number' => '8820134455', 'account_holder' => 'Rafi Fernandito', 'color' => '#0060AF', 'is_active' => true, 'sort_order' => 1],
            ['bank_name' => 'Mandiri', 'account_number' => '1400012345678', 'account_holder' => 'Rafi Fernandito', 'color' => '#003D79', 'is_active' => true, 'sort_order' => 2],
            ['bank_name' => 'BRI', 'account_number' => '005601000123456', 'account_holder' => 'Rafi Fernandito', 'color' => '#00529C', 'is_active' => true, 'sort_order' => 3],
            ['bank_name' => 'BNI', 'account_number' => '7731099224', 'account_holder' => 'Rafi Fernandito', 'color' => '#F15A22', 'is_active' => false, 'sort_order' => 4],
        ];

        foreach ($banks as $bank) {
            $color = $bank['color'];
            unset($bank['color']);

            $bank['logo'] = DummyImage::bankLogo(
                'bank/'.strtolower($bank['bank_name']).'.svg',
                $bank['bank_name'],
                $color,
            );

            BankAccount::query()->updateOrCreate(
                ['account_number' => $bank['account_number']],
                $bank,
            );
        }
    }
}
