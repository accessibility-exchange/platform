<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

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
            PaymentMethod::firstOrCreate([
                'name->en' => $method,
            ]);
        }
    }
}
