<?php

namespace App\Observers;

use App\Models\Revision;
use Illuminate\Support\Facades\Storage;

class RevisionObserver
{
    public function updated(Revision $revision): void
    {
        if ($revision->wasChanged('file')) {
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
