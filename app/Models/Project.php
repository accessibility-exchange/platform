<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\Notifiable;
use Makeable\EloquentStatus\HasStatus;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory;
    use HasStatus;
    use HasTranslations;
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
        'team_size',
        'team_has_disability_or_deaf_lived_experience',
        'team_has_other_lived_experience',
        'team_languages',
        'contacts',
        'has_consultant',
        'consultant_name',
        'consultant_id',
        'consultant_responsibilities',
        'team_trainings',
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
        'team_has_disability_or_deaf_lived_experience' => 'boolean',
        'team_has_other_lived_experience' => 'boolean',
        'team_languages' => 'array',
        'contacts' => 'array',
        'has_consultant' => 'boolean',
        'team_trainings' => 'array',
        'team_trainings.*.date' => 'datetime:Y-m-d',
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
        'consultant_responsibilities',
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
     * @return string|false
     */
    public function timespan(): mixed
    {
        if (! $this->start_date) {
            return false;
        }

        if ($this->end_date) {
            if ($this->start_date->translatedFormat('Y') === $this->end_date->translatedFormat('Y')) {
                return $this->start_date->translatedFormat('F') . '&ndash;' . $this->end_date->translatedFormat('F Y');
            } else {
                return $this->start_date->translatedFormat('F Y') . '&ndash;' . $this->end_date->translatedFormat('F Y');
            }
        }

        return $this->start_date > Carbon::now()
            ? __('Starting :date', ['date' => $this->start_date->translatedFormat('F Y')])
            : __('Started :date', ['date' => $this->start_date->translatedFormat('F Y')]);
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

    /**
     * The engagements that are part of this project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class);
    }

    public function consultant_origin()
    {
        if ($this->consultant_name) {
            return 'external';
        }

        return 'platform';
    }

    /**
     * The engagements that are part of this project which have not yet started.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function upcomingEngagements(): HasMany
    {
        // TODO: Filter engagements
        return $this->engagements();
    }

    public function publish(): void
    {
        $this->published_at = date('Y-m-d h:i:s', time());
        $this->save();
        flash(__('Your project page has been published.'), 'success');
    }

    public function unpublish(): void
    {
        $this->published_at = null;
        $this->save();
        flash(__('Your project page has been unpublished.'), 'success');
    }

    public function handleUpdateRequest(mixed $request, int $step = 1): RedirectResponse
    {
        if (! $request->input('publish') || ! $request->input('unpublish')) {
            if ($this->checkStatus('draft')) {
                flash(__('Your draft project has been updated.'), 'success');
            } else {
                flash(__('Your project has been updated.'), 'success');
            }
        }

        if ($request->input('save')) {
            return redirect(\localized_route('projects.edit', ['project' => $this, 'step' => $step]));
        } elseif ($request->input('save_and_previous')) {
            return redirect(\localized_route('projects.edit', ['project' => $this, 'step' => $step - 1]));
        } elseif ($request->input('save_and_next')) {
            return redirect(\localized_route('projects.edit', ['project' => $this, 'step' => $step + 1]));
        } elseif ($request->input('preview')) {
            return redirect(\localized_route('projects.show', $this));
        } elseif ($request->input('publish')) {
            $this->publish();

            return redirect(\localized_route('projects.edit', ['project' => $this, 'step' => $step]));
        } elseif ($request->input('unpublish')) {
            $this->unpublish();

            return redirect(\localized_route('projects.edit', ['project' => $this, 'step' => $step]));
        }

        return redirect(\localized_route('projects.edit', ['project' => $this, 'step' => $step]));
    }
}
