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
            __('Cash'),
            __('Cheque'),
            __('Credit card'),
            __('Gift card'),
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate([
                'name->en' => $method,
                'name->fr' => trans($method, [], 'fr'),
            ]);
        }
    }
}
