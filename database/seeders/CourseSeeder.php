<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Faker\Generator;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);

        Course::factory()
            ->hasModules(3)
            ->has(Quiz::factory()
                ->has(Question::factory(5))
                ->state(function (array $attributes, Course $course) {
                    return [
                        'title' => "{$course->title} quiz",
                        'minimum_score' => '0.75',
                    ];
                })
            )
            ->create([
                'title' => 'Sample course',
                'author' => $faker->company(),
            ]);
    }
}
