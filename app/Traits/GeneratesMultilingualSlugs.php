<?php

namespace App\Traits;

trait GeneratesMultilingualSlugs
{
    public function generateSlugs(mixed $model, string $locale, string $key = 'name'): string
    {
        if (in_array($locale, ['fr', 'lsq'])) {
            return $model->getTranslation($key, 'fr');
        }

        return $model->getTranslation($key, 'en');
    }
}
