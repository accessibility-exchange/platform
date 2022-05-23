<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;
use Makeable\EloquentStatus\HasStatus;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class RegulatedOrganization extends Model
{
    use CascadesDeletes;
    use HasFactory;
    use HasStatus;
    use HasTranslations;
    use HasTranslatableSlug;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'type',
        'languages',
        'locality',
        'region',
        'about',
        'accessibility_and_inclusion_links',
        'social_links',
        'website_link',
        'published_at',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'languages' => 'array',
        'accessibility_and_inclusion_links' => 'array',
        'published_at' => 'datetime:Y-m-d',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string|array<string>
     */
    protected mixed $cascadeDeletes = [
        'users',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = [
        'name',
        'slug',
        'about',
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
     * Get the community member's social links.
     *
     * @return array
     */
    public function getSocialLinksAttribute(): array
    {
        if (! is_null($this->attributes['social_links'])) {
            return array_filter(json_decode($this->attributes['social_links'], true));
        }

        return [];
    }

    /**
     * Get the community member's links.
     *
     * @return array
     */
    public function getAccessibilityAndInclusionLinksAttribute(): array
    {
        if (! is_null($this->attributes['accessibility_and_inclusion_links'])) {
            return array_filter(json_decode($this->attributes['accessibility_and_inclusion_links'], true));
        }

        return [];
    }

    /**
     * Get the route prefix for the model.
     *
     * @return string
     */
    public function getRoutePrefix(): string
    {
        return 'regulated-organizations';
    }

    /**
     * Publish the regulated organization.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->published_at = date('Y-m-d h:i:s', time());
        $this->save();
        flash(__('Your regulated organization page has been published.'), 'success');
    }

    /**
     * Unpublish the regulated organization.
     *
     * @return void
     */
    public function unpublish(): void
    {
        $this->published_at = null;
        $this->save();
        flash(__('Your regulated organization page has been unpublished.'), 'success');
    }

    /**
     * Get the users that are associated with this federally regulated organization.
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
     * Does the federally regulated organization have more than one administrator?
     */
    public function administrators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'membership')
            ->using('\App\Models\Membership')
            ->wherePivot('role', 'admin');
    }

    /**
     * Determine if the given email address belongs to a user in the federally regulated organization.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email): bool
    {
        return $this->users->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given email address belongs to an administrator in the federally regulated organization.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasAdministratorWithEmail(string $email): bool
    {
        return $this->administrators->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Get the invitations associated with this federally regulated organization.
     *
     * @return MorphMany
     */
    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'inviteable');
    }

    /**
     * The sectors that belong to the federally regulated organization.
     *
     * @return BelongsToMany
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    /**
     * Get the projects that belong to this federally regulated organization.
     *
     * @return MorphMany
     */
    public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this federally regulated organization that are in progress.
     *
     * @return MorphMany
     */
    public function currentProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this federally regulated organization that have been completed.
     *
     * @return MorphMany
     */
    public function pastProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this federally regulated organization that haven't started yet.
     *
     * @return MorphMany
     */
    public function futureProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * The community members who have identified themselves with the federally regulated organization.
     *
     * @return BelongsToMany
     */
    public function communityMembers(): BelongsToMany
    {
        return $this->belongsToMany(CommunityMember::class, 'community_member_regulated_org');
    }

    /**
     * Has the user added any details to the regulated organization?
     *
     * @return bool
     */
    public function hasAddedDetails(): bool
    {
        return ! is_null($this->languages);
    }
}
