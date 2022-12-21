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

    protected $fillable = [
        'order',
        'question',
    ];

    protected $casts = [
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
            Choice::query()->whereIn('id', $this->choices->modelKeys()),
            label: fn (Choice $choice) => $choice->getTranslation('label', locale())
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
