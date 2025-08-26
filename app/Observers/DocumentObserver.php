<?php

namespace App\Observers;

use App\Filament\Resources\RevisionResource;
use App\Models\Document;
use App\Models\Revision;
use Illuminate\Support\Facades\Storage;

class DocumentObserver
{
    public function updated(Document $document): void
    {
        if ($document->wasChanged('name')) {
            $updatedName = $document->getTranslations('name');

            /** @var Revision */
            foreach ($document->revisions as $revision) {
                foreach ($revision->getTranslations('file') as $lang => $file) {
                    $filename = RevisionResource::getFilename($lang, pathinfo(public_path($file), PATHINFO_EXTENSION), $document, $revision->date->format('Y-m-d'));
                    $revision->setTranslation('file', $lang, "documents/$filename");
                    $revision->save();
                    Storage::disk('documents-s3')->move($file, "documents/$filename");
                }
            }
        }
    }
}
