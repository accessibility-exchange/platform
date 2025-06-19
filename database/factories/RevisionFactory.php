<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

use function Pest\Faker\fake;

class RevisionFactory extends Factory
{
    public function definition(): array
    {
        $date = fake()->date('Y-m-d');

        return [
            'document_id' => Document::factory(),
            'date' => $date,
            'file' => ['en' => "documents/example-document-$date-en.pdf"],
        ];
    }
}
