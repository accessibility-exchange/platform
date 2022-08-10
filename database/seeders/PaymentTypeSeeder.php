<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            __('Cheque'),
            __('Gift card'),
            __('Cash'),
            __('E-transfer'),
        ];

        foreach ($methods as $method) {
            PaymentType::firstOrCreate([
                'name->en' => $method,
                'name->fr' => trans($method, [], 'fr'),
            ]);
        }
    }
}
