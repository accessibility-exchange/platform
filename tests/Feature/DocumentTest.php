<?php

use App\Models\Document;
use App\Models\Revision;

test('documents can have many revisions', function () {
    $document = Document::factory()
        ->has(Revision::factory()->count(3), 'revisions')
        ->create();

    expect($document->revisions)->toHaveCount(3)
        ->and($document->revisions->first()->document_id)->toBe($document->id);
});
