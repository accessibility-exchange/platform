<?php

namespace App\Traits;

trait GeneratesMultilingualSlugs
{
    public function generateSlugs(mixed $model, string $locale): string
    {
        if (in_array($locale, ['fr', 'lsq'])) {
            return $model->getTranslation('title', 'fr');
        }

        return $model->getTranslation('title', 'en');
    }
}
