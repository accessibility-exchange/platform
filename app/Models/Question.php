<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Question extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'question',
        'choices',
        'correct_choices',
    ];

    protected $casts = [
        'question' => 'array',
        'choices' => 'array',
        'correct_choices' => 'array',
    ];

    public array $translatable = [
        'question',
        'choices',
    ];

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class);
    }
}
