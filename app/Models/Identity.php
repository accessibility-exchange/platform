<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\LaravelOptions\Selectable;
use Spatie\LaravelOptions\SelectOption;
use Spatie\Translatable\HasTranslations;

class Identity extends Model implements Selectable
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'clusters',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'clusters' => 'array',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    public function toSelectOption(): SelectOption
    {
        return new SelectOption(
            $this->getTranslation('name', locale()),
            $this->id,
            ['hint' => $this->getTranslation('description', locale())]
        );
    }

    public function communityConnectors(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class, 'individual_identity_connections');
    }
}
