<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GenderIdentity extends Model
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
}
