<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DefinedTerm extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'term',
        'definition',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'term' => 'array',
        'definition' => 'array',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = [
        'term',
        'definition',
    ];
}
