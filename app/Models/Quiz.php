<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

/** @property int $questions_count */
class Quiz extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $attributes = [
        'minimum_score' => 1,
    ];

    protected $fillable = [
        'minimum_score',
        'title',
        'order',
    ];

    protected $casts = [
        'minimum_score' => 'float',
        'title' => 'array',
        'order' => 'array',
    ];

    public array $translatable = [
        'title',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('score')
            ->withTimestamps();
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getQuestionsInOrder()
    {
        if ($this->order) {
            $orderedQuestions = collect([]);
            foreach ($this->order as $id) {
                $orderedQuestions->push(Question::find($id));
            }

            return $orderedQuestions->merge($this->questions)->unique('id')->values();
        }

        return $this->questions;
    }
}
