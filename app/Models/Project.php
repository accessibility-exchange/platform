<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
        'regulated_organization_id',
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
     * Has the project started?
     *
     * @return bool
     */
    public function started(): bool
    {
        return $this->start_date < Carbon::now();
    }

    /**
     * Get the project team's trainings.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function teamTrainings(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value) {
                    $trainings = json_decode($value, true);
                    $trainings = array_map(function ($training) {
                        $date = new Carbon($training['date']);
                        $training['date'] = $date->translatedFormat('F j, Y');

                        return $training;
                    }, $trainings);

                    return $trainings;
                }

                return $value;
            },
        );
    }

    /**
     * Get the project's timeframe.
     *
     * @return string
     */
    public function timeframe(): String
    {
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
     * The federally regulated organization that created the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regulatedOrganization(): BelongsTo
    {
        return $this->belongsTo(RegulatedOrganization::class);
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
        return $this->regulatedOrganization->belongsToMany(Sector::class);
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

    /**
     * Determine whether a project's accessibility consultant was engaged throught the platform or externally.
     *
     * @return string
     */
    public function consultant_origin(): String
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
        return $this->engagements(); // TODO: Filter engagements
    }

    /**
     * Get the Community Member assigned to the project as an accessibility consultant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accessibilityConsultant(): BelongsTo
    {
        return $this->belongsTo(CommunityMember::class, 'consultant_id');
    }

    /**
     * Get a description of the project team's lived experience.
     *
     * @return string
     */
    public function teamExperience(): String
    {
        if ($this->team_has_disability_or_deaf_lived_experience && $this->team_has_other_lived_experience) {
            return __('Our team includes people with disabilities and/or Deaf people as well as people from other equity-seeking groups.');
        }

        if ($this->team_has_disability_or_deaf_lived_experience) {
            return __('Our team includes people with disabilities and/or Deaf people.');
        }

        if ($this->team_has_other_lived_experience) {
            return __('Our team includes people from equity-seeking groups.');
        }

        return __('Our team does not include people with disabilities and/or Deaf people or people from other equity-seeking groups.');
    }

    /**
     * Publish the project.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->published_at = date('Y-m-d h:i:s', time());
        $this->save();
        flash(__('Your project page has been published.'), 'success');
    }

    /**
     * Unpublish the project.
     *
     * @return void
     */
    public function unpublish(): void
    {
        $this->published_at = null;
        $this->save();
        flash(__('Your project page has been unpublished.'), 'success');
    }

    /**
     * Handle a request to update the project, redirecting to the appropriate page and displaying the appropriate flash message.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * The matching strategy attached to this project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function matchingStrategy(): MorphOne
    {
        return $this->morphOne(MatchingStrategy::class, 'matchable');
    }
}
