<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Revision extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'file',
    ];

    protected $casts = [
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
