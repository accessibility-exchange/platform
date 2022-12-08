<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\LaravelOptions\Options;
use Spatie\Translatable\HasTranslations;

class Question extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $attributes = [
        'minimum_choices' => 1,
    ];

    protected $fillable = [
        'minimum_choices',
        'order',
        'question',
    ];

    protected $casts = [
        'minimum_choices' => 'integer',
        'order' => 'integer',
        'question' => 'array',
    ];

    public array $translatable = [
        'question',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
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
