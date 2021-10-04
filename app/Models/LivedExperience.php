<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LivedExperience extends Model
{
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'array',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
    ];
}
