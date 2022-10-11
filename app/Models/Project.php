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
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Makeable\EloquentStatus\HasStatus;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Project extends Model
{
    use HasContactPerson;
    use HasFactory;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasRelationships;
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
        'team_languages',
        'team_trainings',
        'seeking_consultant',
        'consultant_name',
        'individual_consultant_id',
        'consultant_responsibilities',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_vrs',
        'preferred_contact_method',
        'contact_person_response_time',
        'estimate_requested_at',
        'estimate_returned_at',
        'estimate_approved_at',
        'agreement_received_at',
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
        'team_size' => 'array',
        'team_has_disability_or_deaf_lived_experience' => 'boolean',
        'team_languages' => 'array',
        'team_trainings' => 'array',
        'seeking_consultant' => 'boolean',
        'consultant_responsibilities' => 'array',
        'contact_person_phone' => E164PhoneNumberCast::class.':CA',
        'contact_person_vrs' => 'boolean',
        'contact_person_response_time' => 'array',
        'estimate_requested_at' => 'datetime:Y-m-d',
        'estimate_returned_at' => 'datetime:Y-m-d',
        'estimate_approved_at' => 'datetime:Y-m-d',
        'agreement_received_at' => 'datetime:Y-m-d',
        'estimate_or_agreement_updated_at' => 'datetime:Y-m-d',
        'estimate_or_agreement_status' => 'integer',
    ];

    public array $translatable = [
        'name',
        'goals',
        'scope',
        'out_of_scope',
        'outcome_analysis_other',
        'outcomes',
        'consultant_responsibilities',
        'contact_person_response_time',
        'team_size',
    ];

    public function getRoutePrefix(): string
    {
        return 'projects';
    }

    public function getRoutePlaceholder(): string
    {
        return 'project';
    }

    public function singularName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => __('project'),
        );
    }

    public function routeNotificationForMail(Notification $notification): array
    {
        return [$this->contact_person_email => $this->contact_person_name];
    }

    public function routeNotificationForVonage(Notification $notification): string
    {
        return $this->contact_person_phone;
    }

    public function getStartedAttribute(): bool
    {
        return $this->start_date?->lessThan(Carbon::now()) ?? false;
    }

    public function getFinishedAttribute(): bool
    {
        return $this->end_date?->lessThan(Carbon::now()) ?? false;
    }

    public function getStatusAttribute(): string
    {
        if ($this->checkStatus('draft')) {
            return __('Draft');
        } elseif (! $this->started) {
            return __('Upcoming');
        } elseif (! $this->finished) {
            return __('In progress');
        }

        return __('Complete');
    }

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
                        if (isset($training['date'])) {
                            $date = new Carbon($training['date']);
                            $training['date'] = $date->format('Y-m-d');
                        }

                        return $training;
                    }, $trainings);
                }

                return null;
            },
        );
    }

    public function isPublishable(): bool
    {
        $publishRules = [
            'contact_person_email' => 'required_without:contact_person_phone',
            'contact_person_name' => 'required',
            'contact_person_phone' => 'required_if:contact_person_vrs,true|required_without:contact_person_email',
            'contact_person_response_time' => 'required',
            'contact_person_response_time.en' => 'required_without:contact_person_response_time.fr',
            'contact_person_response_time.fr' => 'required_without:contact_person_response_time.en',
            'end_date' => 'required',
            'goals.en' => 'required_without:goals.fr',
            'goals.fr' => 'required_without:goals.en',
            'languages' => 'required',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'outcome_analysis' => 'required',
            'preferred_contact_method' => 'required',
            'projectable_id' => 'required',
            'projectable_type' => 'required',
            'regions' => 'required',
            'scope.en' => 'required_without:scope.fr',
            'scope.fr' => 'required_without:scope.en',
            'start_date' => 'required',
            'team_languages' => 'required',
            'team_trainings.*.date' => 'required',
            'team_trainings.*.name' => 'required',
            'team_trainings.*.trainer_name' => 'required',
            'team_trainings.*.trainer_url' => 'required',
        ];

        try {
            Validator::validate($this->toArray(), $publishRules);
        } catch (ValidationException $exception) {
            return false;
        }

        if ($this->projectable instanceof RegulatedOrganization && ! $this->impacts()->count()) {
            return false;
        }

        return true;
    }

    public function timeframe(): string
    {
        if ($this->start_date->translatedFormat('Y') === $this->end_date->translatedFormat('Y')) {
            return $this->start_date->translatedFormat('F').'&ndash;'.$this->end_date->translatedFormat('F Y');
        } else {
            return $this->start_date->translatedFormat('F Y').'&ndash;'.$this->end_date->translatedFormat('F Y');
        }
    }

    public function regulatedOrganization(): BelongsTo
    {
        return $this->belongsTo(RegulatedOrganization::class);
    }

    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class);
    }

    public function participants(): HasManyDeep
    {
        return $this->hasManyDeep(Individual::class, [Engagement::class, 'engagement_individual']);
    }

    public function organizationalParticipants(): HasManyDeep
    {
        return $this->hasManyDeep(Organization::class, [Engagement::class, 'engagement_organization']);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Individual::class, 'individual_consultant_id');
    }

    public function organizationalConsultant(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organizational_consultant_id');
    }

    public function getConsultantOriginAttribute(): string
    {
        if ($this->consultant_name) {
            return 'external';
        }

        return 'platform';
    }

    public function getHasConsultantAttribute(): bool
    {
        return $this->consultant_name || $this->individual_consultant_id;
    }

    public function upcomingEngagements(): HasMany
    {
        return $this->engagements(); // TODO: Filter engagements
    }

    public function teamExperience(): string
    {
        if ($this->team_has_disability_or_deaf_lived_experience) {
            return __('Our team has people with lived and living experiences of disability or being Deaf.');
        }

        return __('Our team does not have people with lived and living experiences of disability or being Deaf.');
    }

    public function matchingStrategy(): MorphOne
    {
        return $this->morphOne(MatchingStrategy::class, 'matchable');
    }

    public function projectable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'projectable_type', 'projectable_id');
    }
}
