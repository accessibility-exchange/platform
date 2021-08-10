<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Organization extends Model
{
    use CascadesDeletes;
    use HasFactory;
    use HasSlug;
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
     * The relationships that should be deleted when an organization is deleted.
     *
     * @var array
     */
    protected $cascadeDeletes = [
        'users',
    ];

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
        return 'organization';
    }

    /**
     * Get the route prefix for the model.
     *
     * @return string
     */
    public function getRoutePrefix()
    {
        return 'organizations';
    }

    /**
     * Get the users that are associated with this organization.
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
     * Does the organization have more than one administrator?
     */
    public function administrators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'membership')
            ->using('\App\Models\Membership')
            ->wherePivot('role', 'admin');
    }

    /**
     * Determine if the given email address belongs to a user in the organization.
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
     * Determine if the given email address belongs to an administrator in the organization.
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
     * Get the invitations associated with this organization.
     */
    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'inviteable');
    }
}
