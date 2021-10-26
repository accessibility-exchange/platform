<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = [
            'Accessible consultation',
            'Intersectional outreach',
            'Contracts',
            'Privacy',
            'Disability knowledge',
        ];

        foreach ($topics as $topic) {
            DB::table('topics')->insert([
                'name' => json_encode(['en' => $topic]),
            ]);
        }
    }
}
