<?php

namespace App\Observers;

use App\Filament\Resources\RevisionResource;
use App\Models\Document;
use App\Models\Revision;
use Illuminate\Support\Facades\Storage;

class RevisionObserver
{
    public function updated(Revision $revision): void
    {
        if ($revision->wasChanged('date')) {
            foreach ($revision->getTranslations('file') as $lang => $file) {
                /** @var Document */
                $document = $revision->document;
                $filename = RevisionResource::getFilename($lang, pathinfo(public_path($file), PATHINFO_EXTENSION), $document, $revision->date->format('Y-m-d'));
                $revision->setTranslation('file', $lang, "documents/$filename");
                $revision->saveQuietly();
                Storage::disk('public')->move($file, "documents/$filename");
            }
        }

        if ($revision->wasChanged('date') && $revision->wasChanged('file')) {
            $originalFiles = $revision->getOriginal('file');
            $updatedFiles = $revision->getTranslations('file');

            foreach ($originalFiles as $lang => $file) {
                if (! is_null($file) && ! isset($updatedFiles[$lang])) {
                    Storage::disk('public')->delete($originalFiles[$lang]);
                }
            }
        }
    }

    public function deleted(Revision $revision): void
    {
        foreach ($revision->getTranslations('file') as $file) {
            Storage::disk('public')->delete($file);
        }
    }
}
