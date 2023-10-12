<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Interpretation
 *
 * @property string $name
 */
class Interpretation extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'namespace',
        'route',
        'route_has_params',
        'video',
    ];

    protected $casts = [
        'video' => 'array',
    ];

    public array $translatable = [
        'video',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => __($value),
        );
    }

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

    public function getContextURL(string $locale = null): ?string
    {
        if ($this->route_has_params) {
            return null;
        }

        $locale ??= locale();
        $anchor = '#'.Str::slug(__($this->getRawOriginal('name'), [], $locale));

        return localized_route($this->route, [], $locale).$anchor;
    }
}
