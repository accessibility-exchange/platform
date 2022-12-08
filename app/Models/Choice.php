<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Choice extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'label',
        'is_answer',
    ];

    protected $casts = [
        'label' => 'array',
        'is_answer' => 'boolean',
    ];

    public array $translatable = [
        'label',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
