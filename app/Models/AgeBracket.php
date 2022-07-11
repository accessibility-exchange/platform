<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\LaravelOptions\Selectable;
use Spatie\LaravelOptions\SelectOption;
use Spatie\Translatable\HasTranslations;

class AgeBracket extends Model implements Selectable
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'min',
        'max',
    ];

    protected $casts = [
        'name' => 'array',
        'min' => 'integer',
        'max' => 'integer',
    ];

    public array $translatable = [
        'name',
    ];

    public function toSelectOption(): SelectOption
    {
        return new SelectOption(
            $this->name,
            $this->id
        );
    }

    public function communityConnectors(): MorphToMany
    {
        return $this->morphToMany(Individual::class, 'connectable');
    }
}
