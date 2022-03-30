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

class Project extends Model
{
    use HasFactory;
    use HasStatus;
    use HasTranslations;
    use HasSlug;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'entity_id',
        'languages',
        'start_date',
        'end_date',
        'published_at',
        'goals',
        'scope',
        'out_of_scope',
        'outcomes',
        'public_outcomes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'array',
        'languages' => 'array',
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'published_at' => 'datetime:Y-m-d',
        'public_outcomes' => 'boolean',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = [
        'name',
        'goals',
        'scope',
        'out_of_scope',
        'outcomes',
    ];

    /**
     * The project's steps and the number of corresponding substeps for each.
     *
     * @var array
     */
    public array $substeps = [
        1 => [
            'published_at',
        ],
        2 => [
            'found_participants',
            'confirmed_participants',
        ],
        3 => [
            'scheduled_planning_meeting',
            'notified_of_planning_meeting',
            'prepared_project_orientation',
            'prepared_contractual_documents',
            'booked_access_services_for_planning',
            'finished_planning_meeting',
        ],
        4 => [
            'scheduled_consultation_meetings',
            'notified_of_consultation_meetings',
            'prepared_consultation_materials',
            'booked_access_services_for_consultations',
            'finished_consultation_meetings',
        ],
        5 => [
            'prepared_accessibility_plan',
            'prepared_follow_up_plan',
            'shared_plans_with_participants',
        ],
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
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
     * Has the project started?
     *
     * @return bool
     */
    public function started(): bool
    {
        return $this->start_date < Carbon::now();
    }

    /**
     * Get the project's timespan.
     *
     * @return string
     */
    public function timespan(): string
    {
        if ($this->end_date) {
            if ($this->start_date->translatedFormat('Y') === $this->end_date->translatedFormat('Y')) {
                return $this->start_date->translatedFormat('F') . '&mdash;' . $this->end_date->translatedFormat('F Y');
            } else {
                return $this->start_date->translatedFormat('F Y') . '&mdash;' . $this->end_date->translatedFormat('F Y');
            }
        }

        return $this->start_date > Carbon::now()
            ? __('project.starting', ['date' => $this->start_date->translatedFormat('F Y')])
            : __('project.started', ['date' => $this->start_date->translatedFormat('F Y')]);
    }

    /**
     * The entity that created the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * The impacts that the project aims to have.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    /**
     * The sectors that the project is working within.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sectors(): BelongsToMany
    {
        return $this->entity->belongsToMany(Sector::class);
    }
}
