<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Profile extends Model
{
    use HasFactory;
    use HasSlug;
    use HasTranslations;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'bio',
        'locality',
        'region',
        'birth_date',
        'pronouns',
        'creator',
        'creator_name',
        'creator_relationship',
        'visibility',
        'status',
        'user_id',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the user that has this consultant profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
