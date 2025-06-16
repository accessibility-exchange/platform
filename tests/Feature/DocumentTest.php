<?php

use App\Models\Document;
use App\Models\Revision;

use function Pest\Faker\fake;

test('documents can have many revisions', function () {
    $document = Document::factory()
        ->has(Revision::factory()->count(3), 'revisions')
        ->create();

    expect($document->revisions)->toHaveCount(3)
        ->and($document->revisions->first()->document_id)->toBe($document->id);
});

test('document observer renames files when related document is renamed', function () {
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

    Storage::disk('public')->put("storage/documents/example-document-$date-en.txt", 'English content');

    $document->setTranslation('name', 'en', 'Test Document');
    $document->save();

    expect($document->revisions->first()->getTranslation('file', 'en'))->toBe("documents/test-document-$date-en.txt");
});
