<?php

namespace App\Models;

use App\Observers\RevisionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\AccessSupport
 *
 * @property Document $document
 */
#[ObservedBy([RevisionObserver::class])]
class Revision extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'date',
        'file',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'file' => 'array',
    ];

    public array $translatable = [
        'file',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function getHasEnglishAttribute(): bool
    {
        return ! $this->getTranslation('file', 'en', false) === false;
    }

    public function getHasFrenchAttribute(): bool
    {
        return ! $this->getTranslation('file', 'fr', false) === false;
    }
}
