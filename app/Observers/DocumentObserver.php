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
                    $name = Str::slug($updatedName[$lang]);
                    $date = $revision->created_at->format('Y-m-d');
                    $extension = pathinfo(public_path($file), PATHINFO_EXTENSION);
                    $revision->setTranslation('file', $lang, "documents/$name-$date-$lang.$extension");
                    $revision->save();
                    Storage::disk('public')->move($file, "documents/$name-$date-$lang.$extension");
                }
            }
        }
    }
}
