<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DefinedTerm extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'term',
        'definition',
    ];

    protected $casts = [
        'term' => 'array',
        'definition' => 'array',
    ];

    public array $translatable = [
        'term',
        'definition',
    ];
}
