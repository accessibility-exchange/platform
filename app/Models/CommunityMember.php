<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
        'phone',
        'email',
        'preferred_contact_method',
        'preferred_contact_person',
        'working_languages',
        'vrs',
        'other_links',
        'status',
        'user_id',
        'age_group',
        'rural_or_remote',
        'lived_experience',
        'skills_and_strengths',
        'relevant_experiences',
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
        'links' => 'array',
        'other_links' => 'array',
        'relevant_experiences' => 'array',
        'support_people' => 'array',
        'languages' => 'array',
        'working_languages' => 'array',
        'rural_or_remote' => 'boolean',
        'vrs' => 'boolean',
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
    public function getPhoneAttribute($value): string
    {
        return str_replace(['-', '(', ')', '.', ' '], '', $value);
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
     * The projects that the community member is interested in.
     */
    public function projectsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_of_interest');
    }

    /**
     * The engagements that the community member belongs to.
     */
    public function engagements(): BelongsToMany
    {
        return $this->belongsToMany(Engagement::class);
    }

    /**
     * The entities that the community member has identified themself with.
     */
    public function regulatedOrganizations(): BelongsToMany
    {
        return $this->belongsToMany(RegulatedOrganization::class, 'community_member_regulated_org');
    }

    /**
     * Get the roles belonging to the community member.
     *
     * @return BelongsToMany
     */
    public function communityRoles(): BelongsToMany
    {
        return $this->belongsToMany(CommunityRole::class);
    }

    /**
     * Has the user added any details to the community member?
     *
     * @return bool
     */
    public function hasAddedDetails(): bool
    {
        return ! is_null($this->region);
    }

    /**
     * Is the community member a participant?
     *
     * @return bool
     */
    public function isParticipant(): bool
    {
        $participantRole = CommunityRole::where('name->en', 'Consultation participant')->first();

        return $this->communityRoles->contains($participantRole);
    }

    /**
     * Is the community member an accessibility consultant?
     *
     * @return bool
     */
    public function isConsultant(): bool
    {
        $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

        return $this->communityRoles->contains($consultantRole);
    }

    /**
     * Is the community member a community connector?
     *
     * @return bool
     */
    public function isConnector(): bool
    {
        $connectorRole = CommunityRole::where('name->en', 'Community connector')->first();

        return $this->communityRoles->contains($connectorRole);
    }

    /**
     * Publish the community member page.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->published_at = date('Y-m-d h:i:s', time());
        $this->save();
        flash(__('Your community member page has been published.'), 'success');
    }

    /**
     * Unpublish the community member page.
     *
     * @return void
     */
    public function unpublish(): void
    {
        $this->published_at = null;
        $this->save();
        flash(__('Your community member page has been unpublished.'), 'success');
    }

    /**
     * Get all the lived experiences that the community member can connect with.
     *
     * @return MorphToMany
     */
    public function livedExperienceConnections(): MorphToMany
    {
        return $this->morphedByMany(LivedExperience::class, 'connectable');
    }

    /**
     * Get all the communities that the community member can connect with.
     *
     * @return MorphToMany
     */
    public function communityConnections(): MorphToMany
    {
        return $this->morphedByMany(Community::class, 'connectable');
    }

    /**
     * Get all the age groups that the community member can connect with.
     *
     * @return MorphToMany
     */
    public function ageGroupConnections(): MorphToMany
    {
        return $this->morphedByMany(AgeGroup::class, 'connectable');
    }

    /**
     * Handle a request to update the community member, redirecting to the appropriate page and displaying the appropriate flash message.
     *
     * @param mixed $request
     * @param int $step
     * @return RedirectResponse
     */
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
