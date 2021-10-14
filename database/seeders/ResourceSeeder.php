<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrFail();
        if ($user) {
            Resource::factory()
                ->count(50)
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}
