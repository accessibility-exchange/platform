<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\LaravelOptions\Selectable;
use Spatie\LaravelOptions\SelectOption;
use Spatie\Translatable\HasTranslations;

class LivedExperience extends Model implements Selectable
{
    use HasTranslations;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public array $translatable = [
        'name',
    ];

    public function toSelectOption(): SelectOption
    {
        return new SelectOption(
            $this->getTranslation('name', locale()),
            $this->id
        );
    }

    public function communityConnectors(): MorphToMany
    {
        return $this->morphToMany(Individual::class, 'connectable');
    }
}
