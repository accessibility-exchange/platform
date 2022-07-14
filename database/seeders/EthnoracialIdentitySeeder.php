<?php

namespace Database\Seeders;

use App\Models\EthnoracialIdentity;
use Illuminate\Database\Seeder;

class EthnoracialIdentitySeeder extends Seeder
{
    public function run()
    {
        $identities = [
            [
                'name' => __('White'),
            ],
            [
                'name' => __('Black'),
            ],
            [
                'name' => __('East Asian'),
            ],
            [
                'name' => __('Asian'),
            ],
            [
                'name' => __('South Asian'),
            ],
            [
                'name' => __('Southeast Asian'),
            ],
            [
                'name' => __('Middle Eastern'),
            ],
            [
                'name' => __('Latin American'),
            ],
            [
                'name' => __('African'),
            ],
        ];

        foreach ($identities as $identity) {
            EthnoracialIdentity::firstOrCreate([
                'name->en' => $identity['name'],
                'name->fr' => trans($identity['name'], [], 'fr'),
                'description->en' => $identity['description'] ?? null,
                'description->fr' => isset($identity['description']) ? trans($identity['description'], [], 'fr') : null,
            ]);
        }
    }
}
