<?php

use App\Models\Document;
use App\Models\Revision;
use Illuminate\Support\Carbon;

test('documents can have many revisions', function () {
    $document = Document::factory()
        ->has(Revision::factory()->count(3), 'revisions')
        ->create();

    expect($document->revisions)->toHaveCount(3)
        ->and($document->revisions->first()->document_id)->toBe($document->id);
});

test('document observer renames files when related document is renamed', function () {
    Storage::fake('public');

    $document = Document::factory()
        ->hasRevisions(1, function (array $attributes, Document $document) {
            return [
                'created_at' => Carbon::create('2025-06-12'),
                'file' => [
                    'en' => 'documents/example-document-2025-06-12-en.txt',
                ],
            ];
        })
        ->create();

    Storage::disk('public')->put('storage/documents/example-document-2025-06-12-en.txt', 'English content');

    $document->setTranslation('name', 'en', 'Test Document');
    $document->save();

    expect($document->revisions->first()->getTranslation('file', 'en'))->toBe('documents/test-document-2025-06-12-en.txt');
});
