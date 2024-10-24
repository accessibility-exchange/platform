<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Format extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public $translatable = [
        'name',
    ];
}
