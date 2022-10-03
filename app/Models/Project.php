<?php

namespace App\Models;

use App\Traits\HasContactPerson;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
                        $date = new Carbon($training['date']);
                        $training['date'] = $date->format('Y-m-d');

                        return $training;
                    }, $trainings);
                }

                return null;
            },
        );
    }

    public function isPublishable(): bool
    {
        return true; // TODO: add appropriate checks.
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

    public function scopeStatuses($query, $statuses)
    {
        // for ($i = 0; $i < sizeof($statuses); $i ++) {
        //     if ($i === 0) {
        //         if (array_values($statuses)[$i] === 'upcoming') {
        //             $query->where('start_date', '>', Carbon::now());
        //         } elseif (array_values($statuses)[$i] === 'inProgress') {
        //             $query->where([
        //                 ['start_date', '<', Carbon::now()],
        //                 ['end_date', '>', Carbon::now()]]);
        //         } elseif (array_values($statuses)[$i] === 'completed') {
        //             $query->where('end_date', '<', Carbon::now());
        //         }
        //     } else {
        //         if (array_values($statuses)[$i] === 'upcoming') {
        //             $query->orWhere('start_date', '>', Carbon::now());
        //         } elseif (array_values($statuses)[$i] === 'inProgress') {
        //             $query->orWhere([
        //                 ['start_date', '<', Carbon::now()],
        //                 ['end_date', '>', Carbon::now()]]);
        //         } elseif (array_values($statuses)[$i] === 'completed') {
        //             $query->orWhere('end_date', '<', Carbon::now());
        //         }
        //     }
        // }

        // for ($i = 0; $i < sizeof($statuses); $i ++) {
        //     $method = 'orWhere';
        //     if ($i === 0) {
        //         $method = 'where';
        //     }

        //     if (array_values($statuses)[$i] === 'upcoming') {
        //         $query->$method('start_date', '>', Carbon::now());
        //     } elseif (array_values($statuses)[$i] === 'inProgress') {
        //         $query->$method([
        //             ['start_date', '<', Carbon::now()],
        //             ['end_date', '>', Carbon::now()]]);
        //     } elseif (array_values($statuses)[$i] === 'completed') {
        //         $query->$method('end_date', '<', Carbon::now());
        //     }
        // }

        $method = 'where';

        foreach ($statuses as $status) {
            if ($status === 'upcoming') {
                $query->$method('start_date', '>', Carbon::now());
            } elseif ($status === 'inProgress') {
                $query->$method([
                    ['start_date', '<', Carbon::now()],
                    ['end_date', '>', Carbon::now()], ]);
            } elseif ($status === 'completed') {
                $query->$method('end_date', '<', Carbon::now());
            }

            do {
                $method = 'orWhere';
            } while ($method === 'where');
        }

        return $query;
    }

    public function scopeSeekings($query, $seekings)
    {
        for ($i = 0; $i < count($seekings); $i++) {
            if ($i === 0) {
                if (in_array('participants', $seekings, true)) {
                    $query->whereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('recruitment', 'open-call');
                    });
                } elseif (in_array('connectors', $seekings, true)) {
                    $query->whereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->withExtraAttributes('seeking_community_connector', true);
                    });
                } elseif (in_array('organizations', $seekings, true)) {
                    $query->whereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('who', 'organization');
                    });
                }
            } else {
                if (in_array('participants', $seekings, true)) {
                    $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('recruitment', 'open-call');
                    });
                } elseif (in_array('connectors', $seekings, true)) {
                    $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->withExtraAttributes('seeking_community_connector', true);
                    });
                } elseif (in_array('organizations', $seekings, true)) {
                    $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('who', 'organization')->whereNull('organization_id');
                    });
                }
            }
        }

        return $query;
    }

    public function scopeInitiators($query, $initiators)
    {
        for ($i = 0; $i < count($initiators); $i++) {
            if ($i === 0) {
                if (array_values($initiators)[$i] === 'organization') {
                    $query->where('projectable_type', 'App\Models\Organization');
                } elseif (array_values($initiators)[$i] === 'regulatedOrganization') {
                    $query->where('projectable_type', 'App\Models\RegulatedOrganization');
                }
            } else {
                if (array_values($initiators)[$i] === 'organization') {
                    $query->orWhere('projectable_type', 'App\Models\Organization');
                } elseif (array_values($initiators)[$i] === 'regulatedOrganization') {
                    $query->orWhere('projectable_type', 'App\Models\RegulatedOrganization');
                }
            }
        }

        return $query;
    }

    // public function scopeSeekingGroups($query, $seekingGroups)
    // {
    //     for ($i = 0; $i < sizeof($seekingGroups); $i ++) {
    //         if ($i === 0) {
    //             if (array_values($seekingGroups)[$i] === '') {
    //                 $query->whereHas('engagements', function (Builder $engagementQuery) {
    //                     $engagementQuery->whereHas('matchingStrategy', function (Builder $matchingStrategyQuery) {
    //                         $matchingStrategyQuery->whereHas('criteria', function (Builder $criteriaQuery) {
    //                             $criteriaQuery->where(['criteriable_type', 'App\Models\DisabilityType']);
    //                         })
    //                     })
    //                 })
    //             } elseif (array_values($seekingGroups)[$i] === '') {
    //             }
    //         } else {

    //         }
    //     }
    // }

    // public function scopeMeetingTypes($query, $meetingTypes)
    // {
    //     for ($i = 0; $i < sizeof($meetingTypes); $i ++) {
    //         if ($i === 0) {
    //             if (array_values($meetingTypes)[$i] === '') {
    //                 $query->whereHas('engagements', function (Builder $query) {
    //                     $query->whereJsonContains($meetingTypes)

                            // Maybe: wrap all these queries in a whereIn query to make sure you are only querying the right format of engagements

                            // Engagement format = 'interviews'
                            // $query->whereJsonContains('meeting_types', $value)

                            // Engagement format = 'workshop', 'focus-group', 'other-sync'
                            // $query->whereHas('meetings', function (Builder $meetingQuery) {
                            //     $meetingQuery->whereJsonContains('meeting_types', $value);
                            // });

    //                 })
    //             } elseif (array_values($meetingTypes)[$i] === '') {
    //             }
    //         } else {

    //         }
    //     }
    // }

    public function scopeCompensations($query, $compensations)
    {
        for ($i = 0; $i < count($compensations); $i++) {
            if ($i === 0) {
                if (array_values($compensations)[$i] === 'paid') {
                    $query->whereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('paid', true);
                    });
                } elseif (array_values($compensations)[$i] === 'volunteer') {
                    $query->whereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('paid', false);
                    });
                }
            } else {
                if (array_values($compensations)[$i] === 'paid') {
                    $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('paid', true);
                    });
                } elseif (array_values($compensations)[$i] === 'volunteer') {
                    $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('paid', false);
                    });
                }
            }
        }

        return $query;
    }

    public function scopeRecruitmentMethods($query, $recruitmentMethods)
    {
        for ($i = 0; $i < count($recruitmentMethods); $i++) {
            if ($i === 0) {
                if (array_values($recruitmentMethods)[$i] === 'open-call') {
                    $query->whereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('recruitment', 'open-call');
                    });
                } elseif (array_values($recruitmentMethods)[$i] === 'connector') {
                    $query->whereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('recruitment', 'connector');
                    });
                }
            } else {
                if (array_values($recruitmentMethods)[$i] === 'open-call') {
                    $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('recruitment', 'open-call');
                    });
                } elseif (array_values($recruitmentMethods)[$i] === 'connector') {
                    $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                        $engagementQuery->where('recruitment', 'connector');
                    });
                }
            }
        }

        return $query;
    }

    public function scopeLocations($query, $locations)
    {
        for ($i = 0; $i < count($locations); $i++) {
            if ($i === 0) {
                $query->whereHas('engagements', function (Builder $engagementQuery) {
                    $engagementQuery->whereHas('matchingStrategy', function (Builder $matchingStrategyQuery) {
                        // First option: province/territory is in 'regions' json
                        $matchingStrategyQuery->whereJsonContains(array_values($locations)[$i], 'regions');
                        // Second option: province/territory is in 'locations.*.region' json
                        $matchingStrategyQuery->whereJsonContains(array_values($locations)[$i], 'locations.*.region');
                    });
                    $engagementQuery->whereIn(array_values($locations)[$i], 'localities');
                });
            } else {
                $query->orWhereHas('engagements', function (Builder $engagementQuery) {
                    $engagementQuery->whereIn(array_values($locations)[$i], 'localities');
                });
            }
        }

        return $query;
    }
}
