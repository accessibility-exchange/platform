<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public array $translatable = [
        'name',
    ];
}
