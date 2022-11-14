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

    // public function getChoices()
    // {
    //     return Options::forArray($this->choices()->pluck('value', 'label'))->toArray();
    // }
}
