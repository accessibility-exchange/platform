<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\Notifiable;
use Makeable\EloquentStatus\HasStatus;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;
use TheIconic\NameParser\Parser as NameParser;

class CommunityMember extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use HasStatus;
    use HasTranslations;
    use InteractsWithMedia;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'locality',
        'region',
        'bio',
        'links',
        'pronouns',
        'picture_alt',
        'creator',
        'phone',
        'email',
        'preferred_contact_methods',
        'roles',
        'hide_location',
        'other_links',
        'areas_of_interest',
        'service_preference',
        'status',
        'user_id',
        'age_group',
        'rural_or_remote',
        'other_lived_experience',
        'lived_experience',
        'skills_and_strengths',
        'work_and_volunteer_experiences',
        'languages',
        'support_people',
        'meeting_types',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hide_location' => 'boolean',
        'links' => 'array',
        'other_links' => 'array',
        'service_preference' => 'array',
        'roles' => 'array',
        'work_and_volunteer_experiences' => 'array',
        'support_people' => 'array',
        'languages' => 'array',
        'rural_or_remote' => 'boolean',
        'preferred_contact_methods' => 'array',
        'meeting_types' => 'array',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = [
        'picture_alt',
        'bio',
        'pronouns',
        'areas_of_interest',
        'other_lived_experience',
        'lived_experience',
        'skills_and_strengths',
    ];

    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('picture')->singleFile();
    }

    /**
     * Register media conversions for the model.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(200)
              ->height(200);
    }

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

    /**
     * Get the community member's links.
     *
     * @return array
     */
    public function getLinksAttribute(): array
    {
        if (! is_null($this->attributes['links'])) {
            return array_filter(json_decode($this->attributes['links'], true));
        }

        return [];
    }

    /**
     * Get the community member's first name.
     *
     * @return string
     */
    public function firstName(): string
    {
        return (new NameParser())->parse($this->attributes['name'])->getFirstname();
    }

    /**
     * Get the community member's phone number.
     *
     * @param string $value
     * @return string
     */
    public function getPhoneNumberAttribute($value): string
    {
        return str_replace(['-', '(', ')', '.', ' '], '', $this->phone);
    }

    /**
     * Get the user that has this community member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The impacts that belong to the community member.
     */
    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    /**
     * The sectors that belong to the community member.
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    /**
     * The payment methods that belong to the community member.
     */
    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
    }

    /**
     * The communities that belong to the community member.
     */
    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class);
    }

    /**
     * The communities that belong to the community member.
     */
    public function livedExperiences(): BelongsToMany
    {
        return $this->belongsToMany(LivedExperience::class);
    }

    /**
     * The access supports that belong to the community member.
     */
    public function accessSupports(): BelongsToMany
    {
        return $this->belongsToMany(AccessSupport::class);
    }

    /**
     * The projects that the community member belongs to.
     */
    public function projectsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_of_interest');
    }

    /**
     * The entities that the community member has identified themself with.
     */
    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class);
    }

    public function publish(): void
    {
        $this->published_at = date('Y-m-d h:i:s', time());
        $this->save();
        flash(__('Your community member page has been published.'), 'success');
    }

    public function unpublish(): void
    {
        $this->published_at = null;
        $this->save();
        flash(__('Your community member page has been unpublished.'), 'success');
    }

    public function handleUpdateRequest(mixed $request, int $step = 1): RedirectResponse
    {
        if (! $request->input('publish') || ! $request->input('unpublish')) {
            if ($this->checkStatus('draft')) {
                flash(__('Your draft community member page has been updated.'), 'success');
            } else {
                flash(__('Your community member page has been updated.'), 'success');
            }
        }

        if ($request->input('save')) {
            return redirect(\localized_route('community-members.edit', ['communityMember' => $this, 'step' => $step]));
        } elseif ($request->input('save_and_previous')) {
            return redirect(\localized_route('community-members.edit', ['communityMember' => $this, 'step' => $step - 1]));
        } elseif ($request->input('save_and_next')) {
            return redirect(\localized_route('community-members.edit', ['communityMember' => $this, 'step' => $step + 1]));
        } elseif ($request->input('preview')) {
            return redirect(\localized_route('community-members.show', $this));
        } elseif ($request->input('publish')) {
            $this->publish();

            return redirect(\localized_route('community-members.edit', ['communityMember' => $this, 'step' => $step]));
        } elseif ($request->input('unpublish')) {
            $this->unpublish();

            return redirect(\localized_route('community-members.edit', ['communityMember' => $this, 'step' => $step]));
        }

        return redirect(\localized_route('community-members.edit', ['communityMember' => $this, 'step' => $step]));
    }
}
