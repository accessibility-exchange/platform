<?php

namespace App\Models;

use App\Traits\HasMultimodalTranslations;
use Carbon\Carbon;
use Hearth\Traits\HasInvitations;
use Hearth\Traits\HasMembers;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\Notifiable;
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
    use HasInvitations;
    use HasMembers;
    use HasMultimodalTranslations;
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
        'service_areas',
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
        'service_areas' => 'array',
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
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function serviceRegions(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => get_regions_from_provinces_and_territories(json_decode($attributes['service_areas']) ?? []),
        );
    }

    /**
     * Get the individual's social links.
     *
     * @return array
     */
    public function getSocialLinksAttribute(): array
    {
        if (isset($this->attributes['social_links']) && ! is_null($this->attributes['social_links'])) {
            return array_filter(json_decode($this->attributes['social_links'], true));
        }

        return [];
    }

    /**
     * Get the individual's accessibility and inclusion links.
     *
     * @return array
     */
    public function getAccessibilityAndInclusionLinksAttribute(): array
    {
        if (isset($this->attributes['accessibility_and_inclusion_links']) && ! is_null($this->attributes['accessibility_and_inclusion_links'])) {
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
     * Get the projects that belong to this regulated organization that are in progress.
     *
     * @return MorphMany
     */
    public function inProgressProjects(): MorphMany
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
     * Get the projects that belong to this regulated organization that have been completed.
     *
     * @return MorphMany
     */
    public function completedProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this regulated organization that haven't started yet.
     *
     * @return MorphMany
     */
    public function upcomingProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Has the user added any details to the regulated organization?
     *
     * @return bool
     */
    public function hasAddedDetails(): bool
    {
        return ! is_null($this->region);
    }

    public function blocks(): MorphToMany
    {
        return $this->morphToMany(User::class, 'blockable');
    }

    public function blockedBy(?User $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $this->blocks()->where('user_id', $user->id)->exists();
    }

    public function notificationRecipients(): MorphToMany
    {
        return $this->morphToMany(User::class, 'notificationable');
    }

    public function isNotifying(?User $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $this->notificationRecipients()->where('user_id', $user->id)->exists();
    }

    /**
     * Handle a request to update the individual, redirecting to the appropriate page and displaying the appropriate flash message.
     *
     * @param mixed $request
     * @return RedirectResponse
     */
    public function handleUpdateRequest(mixed $request): RedirectResponse
    {
        if (! $request->input('publish') || ! $request->input('unpublish')) {
            if ($this->checkStatus('draft')) {
                flash(__('Your draft regulated organization page has been updated.'), 'success');
            } else {
                flash(__('Your regulated organization page has been updated.'), 'success');
            }
        }

        if ($request->input('preview')) {
            return redirect(localized_route('regulated-organizations.show', $this));
        } elseif ($request->input('publish')) {
            $this->publish();

            return redirect(localized_route('regulated-organizations.edit', $this));
        } elseif ($request->input('unpublish')) {
            $this->unpublish();

            return redirect(localized_route('regulated-organizations.edit', $this));
        }

        return redirect(localized_route('regulated-organizations.edit', $this));
    }
}
