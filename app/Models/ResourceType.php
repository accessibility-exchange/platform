<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\ResourceType
 *
 * @property string $name
 */
class ResourceType extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'content_types';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public $translatable = [
        'name',
    ];

    public function resources()
    {
        return $this->hasMany(Resource::class, 'content_type_id');
    }
}
