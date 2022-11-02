<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Question extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'order',
        'question',
        'choices',
        'answers',
    ];

    protected $casts = [
        'order' => 'integer',
        'question' => 'array',
        'choices' => 'array',
        'answers' => 'array',
    ];

    public array $translatable = [
        'question',
        'choices',
        'answers',
    ];
}
