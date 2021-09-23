<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Makeable\EloquentStatus\HasStatus;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;
use TheIconic\NameParser\Parser as NameParser;

class Consultant extends Model
{
    use HasFactory;
    use HasSlug;
    use HasStatus;
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
        'links',
        'locality',
        'region',
        'birth_date',
        'pronouns',
        'creator',
        'creator_name',
        'creator_relationship',
        'status',
        'user_id',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array
     */
    protected $casts = [
        'links' => 'array',
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
     * Get the consultant's age in years.
     */
    public function age()
    {
        return Carbon::parse($this->attributes['birth_date'])->age;
    }

    /**
     * Get the consultant's first name.
     */
    public function firstName()
    {
        return (new NameParser())->parse($this->attributes['name'])->getFirstname();
    }

    /**
     * Get the user that has this consultant consultant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The projects that the consultant belongs to.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
