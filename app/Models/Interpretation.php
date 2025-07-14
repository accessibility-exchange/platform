<?php

namespace App\Models;

/**
 * App\Models\Interpretation
 *
 * @property string $name
 */
class Interpretation extends Video
{
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Interpretation $model) {
            $model->namespace ??= $model->route;
        });

        static::updating(function (Interpretation $model) {
            $model->namespace ??= $model->route;
        });
    }
}
