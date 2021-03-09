<?php

namespace App\Models;

use App\Models\Profile;
use App\Models\Organization;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail
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
        'email',
        'password',
        'locale',
        'theme'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Get the consultant profile associated with the user.
     *
     * @return mixed
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the consulting organizations that belong to this user.
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class)->withPivot('admin');
    }

    /**
     * Determine if the user is a member of a given organization.
     *
     * @param \App\Models\Organization
     * @return bool
     */
    public function isMemberOf(Organization $organization)
    {
        return $this->organizations()
            ->where('organization_id', $organization->id)
            ->exists();
    }

    /**
     * Determine if the user is an administrator of a given organization.
     *
     * @param \App\Models\Organization
     * @return bool
     */
    public function isAdministratorOf(Organization $organization)
    {
        return $this->organizations()
            ->where('organization_id', $organization->id)
            ->where('admin', true)
            ->exists();
    }
}
