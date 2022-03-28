<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ContentType extends Model
{
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array<string, string>
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

    /**
     *
     */
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
