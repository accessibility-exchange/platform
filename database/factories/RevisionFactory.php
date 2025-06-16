<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RevisionFactory extends Factory
{
    public function definition(): array
    {
        $now = Carbon::now()->format('Y-m-d');

        return [
            'document_id' => Document::factory(),
            'date' => $now,
            'file' => ['en' => "documents/example-document-$now-en.pdf"],
        ];
    }
}
