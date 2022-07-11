<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelOptions\Selectable;
use Spatie\LaravelOptions\SelectOption;
use Spatie\Translatable\HasTranslations;

class GenderIdentity extends Model implements Selectable
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'array',
        'name_plural' => 'array',
        'adjective' => 'array',
        'description' => 'array',
    ];

    public array $translatable = [
        'name',
        'name_plural',
        'adjective',
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
}
