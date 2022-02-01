<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * @var array
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
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array
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
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * The past projects that the community member belongs to.
     */
    public function pastProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * The current projects that the community member belongs to.
     */
    public function currentProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->orderBy('start_date');
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

    public function projectMatches(Project $project): array
    {
        $matches = [
            [
                'name' => __('In your location'),
                'value' => in_array($this->region, $project->regions),
            ],
            [
                'name' => __('Accepts your payment methods'),
                'value' => (count($this->paymentMethods->intersect($project->paymentMethods))) > 0 ? true : false,
            ],
            [
                'name' => __('Interested in your sector'),
                'value' => (count($this->sectors->intersect($project->sectors))) > 0 ? true : false,
            ],
            [
                'name' => __('Interested in your project area'),
                'value' => (count($this->impacts->intersect($project->impacts))) > 0 ? true : false,
            ],
        ];

        if (count($project->communities) > 0) {
            $matches[] = [
                'name' => __('Community'),
                'value' => (count($this->communities->intersect($project->communities))) > 0 ? true : false,
            ];
        }

        return $matches;
    }

    public function projectMatch(Project $project): string
    {
        $projectMatches = $this->projectMatches($project);
        $matchCount = array_filter($projectMatches, function ($key) {
            return $key['value'];
        });
        $percentage = count($matchCount) / count($projectMatches);

        if ($percentage == 1) {
            return __('Matches <strong>all</strong> project criteria');
        } elseif ($percentage > 0.5) {
            return __('Matches <strong>most</strong> project criteria');
        } elseif ($percentage > 0) {
            return __('Matches <strong>some</strong> project criteria');
        }

        return __('Doesn’t match any project criteria');
    }
}
