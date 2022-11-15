<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'label',
        'is_answer',
    ];

    protected $casts = [
        'value' => 'string',
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
