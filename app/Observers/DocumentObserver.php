<?php

namespace App\Observers;

use App\Models\Document;
use App\Models\Revision;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentObserver
{
    public function updated(Document $document): void
    {
        if ($document->wasChanged('name')) {
            $updatedName = $document->getTranslations('name');

            /** @var Revision */
            foreach ($document->revisions as $revision) {
                foreach ($revision->getTranslations('file') as $lang => $file) {
                    $filename = $document->getRevisionFilename($revision->created_at, $lang, pathinfo(public_path($file), PATHINFO_EXTENSION), Str::slug($updatedName[$lang]));
                    $revision->setTranslation('file', $lang, "documents/$filename");
                    $revision->save();
                    Storage::disk('public')->move($file, "documents/$filename");
                }
            }
        }
    }
}
