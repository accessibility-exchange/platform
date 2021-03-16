<?php

namespace App\Models;

use App\Models\OrganizationInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Organization extends Model
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
        'locality',
        'region'
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
     * Get the users that are associated with this organization.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->as('membership')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Does the organization have more than one administrator?
     */
    public function administrators()
    {
        return $this->belongsToMany(User::class)->wherePivot('role', 'admin');
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
     * Get the invitations associated with this organization.
     */
    public function organizationInvitations()
    {
        return $this->hasMany(OrganizationInvitation::class);
    }
}
