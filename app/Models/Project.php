<?php

namespace App\Models;

use App\Traits\HasContactPerson;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\Notifiable;
use Makeable\EloquentStatus\HasStatus;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasContactPerson;
    use HasFactory;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasStatus;
    use HasTranslations;
    use Notifiable;

    protected $attributes = [
        'preferred_contact_method' => 'email',
    ];

    protected $fillable = [
        'published_at',
        'projectable_id',
        'projectable_type',
        'languages',
        'name',
        'goals',
        'scope',
        'regions',
        'out_of_scope',
        'start_date',
        'end_date',
        'outcome_analysis',
        'outcome_analysis_other',
        'outcomes',
        'public_outcomes',
        'team_size',
        'team_has_disability_or_deaf_lived_experience',
        'team_has_other_lived_experience',
        'team_languages',
        'team_trainings',
        'seeking_consultant',
        'consultant_name',
        'consultant_id',
        'consultant_responsibilities',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_vrs',
        'preferred_contact_method',
        'contact_person_response_time',
    ];

    protected $casts = [
        'published_at' => 'datetime:Y-m-d',
        'languages' => 'array',
        'name' => 'array',
        'goals' => 'array',
        'scope' => 'array',
        'regions' => 'array',
        'out_of_scope' => 'array',
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'outcome_analysis' => 'array',
        'outcome_analysis_other' => 'array',
        'outcomes' => 'array',
        'public_outcomes' => 'boolean',
        'team_has_disability_or_deaf_lived_experience' => 'boolean',
        'team_has_other_lived_experience' => 'boolean',
        'team_languages' => 'array',
        'team_trainings' => 'array',
        'seeking_consultant' => 'boolean',
        'consultant_responsibilities' => 'array',
        'contact_person_phone' => E164PhoneNumberCast::class.':CA',
        'contact_person_vrs' => 'boolean',
    ];

    public array $translatable = [
        'name',
        'goals',
        'scope',
        'out_of_scope',
        'outcome_analysis_other',
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
                        return array_filter($training);
                    }, $trainings);
                    $trainings = array_filter($trainings);

                    return array_map(function ($training) {
                        $date = new Carbon($training['date']);
                        $training['date'] = $date->translatedFormat('F Y');

                        return $training;
                    }, $trainings);
                }

                return null;
            },
        );
    }

    /**
     * Get the project's timeframe.
     *
     * @return string
     */
    public function timeframe(): string
    {
        if ($this->end_date) {
            if ($this->start_date->translatedFormat('Y') === $this->end_date->translatedFormat('Y')) {
                return $this->start_date->translatedFormat('F').'&ndash;'.$this->end_date->translatedFormat('F Y');
            } else {
                return $this->start_date->translatedFormat('F Y').'&ndash;'.$this->end_date->translatedFormat('F Y');
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
    public function getConsultantOriginAttribute(): string
    {
        if ($this->consultant_name) {
            return 'external';
        }

        return 'platform';
    }

    public function getHasConsultantAttribute(): bool
    {
        return $this->consultant_name || $this->consultant_id;
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
     * Get the Constituency Member assigned to the project as an accessibility consultant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accessibilityConsultant(): BelongsTo
    {
        return $this->belongsTo(Individual::class, 'consultant_id');
    }

    /**
     * Get a description of the project team's lived experience.
     *
     * @return string
     */
    public function teamExperience(): string
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
     * The matching strategy attached to this project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function matchingStrategy(): MorphOne
    {
        return $this->morphOne(MatchingStrategy::class, 'matchable');
    }

    /**
     * Get the model that the project belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function projectable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'projectable_type', 'projectable_id');
    }
}
