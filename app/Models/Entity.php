<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Entity extends Model
{
    use CascadesDeletes;
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
        'locality',
        'region',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [];

    protected $cascadeDeletes = ['users'];

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
     * Get the route placeholder for the model.
     *
     * @return string
     */
    public function getRouteKeyPlaceholder()
    {
        return 'entity';
    }

    /**
     * Get the route prefix for the model.
     *
     * @return string
     */
    public function getRoutePrefix()
    {
        return 'entities';
    }

    /**
     * Get the users that are associated with this entity.
     */
    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'membership')
            ->using('\App\Models\Membership')
            ->as('membership')
            ->withPivot('id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Does the entity have more than one administrator?
     */
    public function administrators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'membership')
            ->using('\App\Models\Membership')
            ->wherePivot('role', 'admin');
    }

    /**
     * Determine if the given email address belongs to a user in the entity.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->users->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given email address belongs to an administrator in the entity.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasAdministratorWithEmail(string $email)
    {
        return $this->administrators->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Get the invitations associated with this entity.
     */
    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'inviteable');
    }

    /**
     * Get the projects that belong to this entity.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this entity that are in progress.
     */
    public function currentProjects(): HasMany
    {
        return $this->hasMany(Project::class)
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this entity that have been completed.
     */
    public function pastProjects(): HasMany
    {
        return $this->hasMany(Project::class)
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this entity that haven't started yet.
     */
    public function futureProjects(): HasMany
    {
        return $this->hasMany(Project::class)
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }
}
