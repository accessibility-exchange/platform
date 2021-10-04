<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            'Cash',
            'Cheque',
            'Credit card',
            'Gift card',
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->insert([
                'name' => json_encode(['en' => $method]),
            ]);
        }
    }
}
