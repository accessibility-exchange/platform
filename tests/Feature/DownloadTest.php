<?php

use App\Enums\UserContext;
use App\Models\Document;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;

test('document revisions can be downloaded', function () {
    Storage::fake('public');

    $date = fake()->date('Y-m-d');

    $document = Document::factory()
        ->hasRevisions(1, [
            'date' => $date,
            'file' => [
                'en' => "documents/example-document-$date-en.txt",
            ],
        ])
        ->create();

    Storage::disk('public')->put("documents/example-document-$date-en.txt", 'English content');

    expect(Storage::disk('public')->exists("documents/example-document-$date-en.txt"))->toBeTrue();

    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => UserContext::Administrator->value]);

    actingAs($user)->post(localized_route('download', [
        'revision' => $document->revisions->first()->id,
        'email' => $user->email,
    ], 'en'))
        ->assertDownload("example-document-$date-en.txt");
});
