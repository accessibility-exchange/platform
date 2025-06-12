<?php

use App\Models\Document;
use App\Models\Revision;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('revisions can belong to documents', function () {
    $document = Document::factory()->create();
    $revision = Revision::factory()->create(['document_id' => $document->id]);

    expect($revision->document())->toBeInstanceOf(BelongsTo::class)
        ->and($revision->document)->toBeInstanceOf(Document::class)
        ->and($revision->document->id)->toBe($document->id);
});

test('has_english attribute reflects presense or absence of English file', function () {
    $revision = Revision::factory()->create();
    $revision->setTranslation('file', 'en', 'path/to/english/file.pdf');
    $revision->save();

    expect($revision->has_english)->toBeTrue();

    $revision->setTranslation('file', 'en', null);
    $revision->save();

    expect($revision->has_english)->toBeFalse();
});

test('has_french attribute reflects presense or absence of French file', function () {
    $revision = Revision::factory()->create();
    $revision->setTranslation('file', 'fr', 'path/to/french/file.pdf');
    $revision->save();

    expect($revision->has_french)->toBeTrue();

    $revision->setTranslation('file', 'fr', null);
    $revision->save();

    expect($revision->has_french)->toBeFalse();
});

test('revision observer deletes files when revision is deleted', function () {
    Storage::fake('public');

    Storage::disk('public')->put('storage/documents/document-2025-06-12-en.txt', 'English content');

    $revision = Revision::factory()->create([
        'file' => [
            'en' => 'storage/documents/document-2025-06-12-en.txt',
        ],
    ]);

    $revision->delete();

    expect(Storage::disk('public')->exists('storage/documents/document-2025-06-12-en.txt'))->toBeFalse();
});

test('revision observer deletes files when file reference is removed', function () {
    Storage::fake('public');

    Storage::disk('public')->put('storage/documents/document-2025-06-12-en.txt', 'English content');

    $revision = Revision::factory()->create([
        'file' => [
            'en' => 'storage/documents/document-2025-06-12-en.txt',
        ],
    ]);

    $revision->setTranslation('file', 'en', null);
    $revision->save();

    expect(Storage::disk('public')->exists('storage/documents/document-2025-06-12-en.txt'))->toBeFalse();
});
