<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AccessSupport extends Model
{
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'in_person',
        'virtual',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'array',
        'in_person' => 'boolean',
        'virtual' => 'boolean',
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
