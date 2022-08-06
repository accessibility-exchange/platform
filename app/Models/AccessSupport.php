<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelOptions\Selectable;
use Spatie\LaravelOptions\SelectOption;
use Spatie\Translatable\HasTranslations;

class AccessSupport extends Model implements Selectable
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'in_person',
        'virtual',
        'documents',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'in_person' => 'boolean',
        'virtual' => 'boolean',
        'documents' => 'boolean',
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
}
