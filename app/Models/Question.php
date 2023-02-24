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

    protected $attributes = [
        'minimum_choices' => 1,
    ];

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

    public function getChoices()
    {
        return Options::forModels(
            Choice::query()->whereIn('id', $this->choices->pluck('id')->toArray()),
            label: fn (Choice $choice) => $choice->getTranslation('label', 'en')
        )->toArray();
    }

    public function getCorrectChoices()
    {
        $correctChoices = [];
        foreach ($this->choices as $choice) {
            if ($choice->is_answer) {
                $correctChoices[] = $choice->id;
            }
        }

        return $correctChoices;
    }
}
