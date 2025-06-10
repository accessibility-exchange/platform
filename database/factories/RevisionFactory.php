<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevisionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'file' => $this->faker->image(storage_path('public/documents'), 512, 384, null, false),
        ];
    }
}
