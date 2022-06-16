<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
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
