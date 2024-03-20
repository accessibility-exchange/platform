<?php

namespace App\Models;

use App\Enums\Compensation;
use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\EngagementSignUpStatus;
use App\Enums\MeetingType;
use App\Enums\ProjectInitiator;
use App\Enums\SeekingForEngagement;
use App\Models\Scopes\EngagementProjectableNotSuspendedScope;
use App\Traits\HasSchemalessAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Makeable\EloquentStatus\HasStatus;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Engagement
 *
 * @property SchemalessAttributes::class $extra_attributes
 */
class Engagement extends Model
{
    use HasFactory;
    use HasRelationships;
    use HasSchemalessAttributes;
    use HasStatus;
    use HasTranslations;

    protected $attributes = [
        'paid' => true,
    ];

    protected $fillable = [
        'project_id',
        'published_at',
        'languages',
        'name',
        'format',
        'ideal_participants',
        'minimum_participants',
        'paid',
        'description',
        'recruitment',
        'who',
        'recruitment',
        'regions',
        'localities',
        'paid',
        'signup_by_date',
        'materials_by_date',
        'complete_by_date',
        'window_start_date',
        'window_end_date',
        'timezone',
        'weekday_availabilities',
        'document_languages',
        'accepted_formats',
        'individual_connector_id',
        'organizational_connector_id',
        'individual_consultant_id',
        'organizational_consultant_id',
        'organization_id',
        'extra_attributes',
        'window_start_time',
        'window_end_time',
        'window_flexibility',
        'meeting_types',
        'street_address',
        'unit_suite_floor',
        'locality',
        'region',
        'postal_code',
        'directions',
        'meeting_software',
        'alternative_meeting_software',
        'meeting_url',
        'additional_video_information',
        'meeting_phone',
        'additional_phone_information',
        'other_accepted_format',
        'open_to_other_formats',
    ];

    protected $casts = [
        'published_at' => 'datetime:Y-m-d',
        'languages' => 'array',
        'name' => 'array',
        'ideal_participants' => 'integer',
        'minimum_participants' => 'integer',
        'description' => 'array',
        'regions' => 'array',
        'localities' => 'array',
        'paid' => 'boolean',
        'payment' => 'array',
        'signup_by_date' => 'datetime:Y-m-d',
        'materials_by_date' => 'datetime:Y-m-d',
        'complete_by_date' => 'datetime:Y-m-d',
        'window_start_date' => 'datetime:Y-m-d',
        'window_end_date' => 'datetime:Y-m-d',
        'window_start_time' => 'datetime:G:i',
        'window_end_time' => 'datetime:G:i',
        'weekday_availabilities' => 'array',
        'meeting_types' => 'array',
        'meeting_phone' => E164PhoneNumberCast::class.':CA',
        'document_languages' => 'array',
        'accepted_formats' => 'array',
        'directions' => 'array',
        'additional_video_information' => 'array',
        'additional_phone_information' => 'array',
        'other_accepted_format' => 'array',
        'window_flexibility' => 'boolean',
        'alternative_meeting_software' => 'boolean',
        'open_to_other_formats' => 'boolean',
    ];

    public array $translatable = [
        'name',
        'description',
        'directions',
        'additional_video_information',
        'additional_phone_information',
        'additional_phone_information',
        'other_accepted_format',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new EngagementProjectableNotSuspendedScope);
    }

    public function singularName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => __('engagement'),
        );
    }

    public function getRoutePrefix(): string
    {
        return 'engagements';
    }

    public function displayFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => EngagementFormat::labels()[$this->format],
        );
    }

    public function meetingDates(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (! $this->meetings->count()) {
                    return null;
                }

                $meetings = $this->meetings->sortBy('date');
                $start = $meetings->first()->date;
                $end = $meetings->pop()->date;

                if ($start->isoFormat('LL') === $end->isoFormat('LL')) {
                    return $start->isoFormat('LL');
                }

                if ($start->format('MM') === $end->format('MM')) {
                    return $start->isoFormat('MMMM D').'–'.$end->isoFormat('D, YYYY');
                }

                return $start->isoFormat('MMMM D').'–'.$end->isoFormat('LL');
            }
        );
    }

    public function meetingTypesIncludes(string $meetingType)
    {
        if ($this->format === 'interviews') {
            return in_array($meetingType, $this->meeting_types ?? []);
        } elseif ($this->meetings->count()) {
            return in_array($meetingType, $this->meetings->pluck('meeting_types')->flatten()->unique()->toArray());
        }

        return false;
    }

    public function displayMeetingTypes(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->format === 'interviews') {
                    return Arr::map($this->meeting_types ?? [], fn ($meeting_type) => MeetingType::labels()[$meeting_type]);
                } elseif ($this->meetings->count()) {
                    return Arr::map($this->meetings->pluck('meeting_types')->flatten()->unique()->toArray(), fn ($meeting_type) => MeetingType::labels()[$meeting_type]);
                } else {
                    return [];
                }
            }
        );
    }

    public function isManageable(): bool
    {
        $manageableRules = [
            'format' => [
                'required_unless:who,organization',
            ],
            'recruitment' => [
                'required_unless:who,organization',
            ],
        ];

        try {
            Validator::validate($this->toArray(), $manageableRules);
        } catch (ValidationException $exception) {
            return false;
        }

        return true;
    }

    public function isPreviewable(): bool
    {
        $weekdayAvailabilitiesRules = [Rule::requiredIf($this->format === 'interviews')];

        $publishRules = [
            'name.*' => 'nullable|string',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'description.*' => 'nullable|string',
            'description.en' => 'required_without:description.fr',
            'description.fr' => 'required_without:description.en',
            'window_start_date' => [
                Rule::requiredIf($this->format === 'interviews'),
            ],
            'window_end_date' => [
                Rule::requiredIf($this->format === 'interviews'),
            ],
            'window_start_time' => [
                Rule::requiredIf($this->format === 'interviews'),
            ],
            'window_end_time' => [
                Rule::requiredIf($this->format === 'interviews'),
            ],
            'timezone' => [
                Rule::requiredIf($this->format === 'interviews'),
            ],
            'weekday_availabilities.monday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.tuesday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.wednesday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.thursday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.friday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.saturday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.sunday' => $weekdayAvailabilitiesRules,
            'meeting_types' => [
                Rule::requiredIf($this->format === 'interviews'),
            ],
            'street_address' => [
                Rule::requiredIf($this->format === 'interviews' && in_array('in_person', $this->meeting_types ?? [])),
            ],
            'locality' => [
                Rule::requiredIf($this->format === 'interviews' && in_array('in_person', $this->meeting_types ?? [])),
            ],
            'region' => [
                Rule::requiredIf($this->format === 'interviews' && in_array('in_person', $this->meeting_types ?? [])),
            ],
            'postal_code' => [
                Rule::requiredIf($this->format === 'interviews' && in_array('in_person', $this->meeting_types ?? [])),
            ],
            'meeting_software' => [
                Rule::requiredIf($this->format === 'interviews' && in_array('web_conference', $this->meeting_types ?? [])),
            ],
            'meeting_url' => [
                Rule::requiredIf($this->format === 'interviews' && in_array('web_conference', $this->meeting_types ?? [])),
            ],
            'meeting_phone' => [
                Rule::requiredIf($this->format === 'interviews' && in_array('phone', $this->meeting_types ?? [])),
            ],
            'materials_by_date' => [
                Rule::requiredIf(in_array($this->format, ['interviews', 'survey', 'other-async'])),
            ],
            'complete_by_date' => [
                Rule::requiredIf(in_array($this->format, ['interviews', 'survey', 'other-async'])),
            ],
            'document_languages' => [
                Rule::requiredIf(in_array($this->format, ['survey', 'other-async'])),
            ],
            'accepted_formats' => [
                Rule::requiredIf($this->format === 'interviews'),
            ],
            'ideal_participants' => [
                Rule::requiredIf($this->who === 'individuals'),
            ],
            'minimum_participants' => [
                Rule::requiredIf($this->who === 'individuals'),
            ],
            'signup_by_date' => [
                Rule::requiredIf($this->who === 'individuals'),
            ],
        ];

        try {
            Validator::validate($this->toArray(), $publishRules);
        } catch (ValidationException $exception) {
            return false;
        }

        if (in_array($this->format, ['workshop', 'focus-group', 'other-sync']) && ! $this->meetings->count()) {
            return false;
        }

        return true;
    }

    public function hasEstimateAndAgreement(): bool
    {
        return $this->project->checkStatus('estimateApproved') && $this->project->checkStatus('agreementReceived');
    }

    public function isPublishable(): bool
    {
        if (! $this->isPreviewable()) {
            return false;
        }

        if ($this->who === 'individuals' && ! $this->hasEstimateAndAgreement()) {
            return false;
        }

        if (! $this->project->projectable->checkStatus('approved')) {
            return false;
        }

        return true;
    }

    public function displayRecruitment(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => EngagementRecruitment::labels()[$this->recruitment],
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'invitationable');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class)->withPivot('status', 'share_access_needs');
    }

    public function accessNeeds(): HasManyDeep
    {
        return $this->hasManyDeep(AccessSupport::class, ['engagement_individual', Individual::class, 'access_support_individual']);
    }

    public function confirmedParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class)->withPivot('share_access_needs')->wherePivot('status', 'confirmed');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Individual::class, 'individual_consultant_id');
    }

    public function organizationalConsultant(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organizational_consultant_id');
    }

    public function connector(): BelongsTo
    {
        return $this->belongsTo(Individual::class, 'individual_connector_id');
    }

    public function organizationalConnector(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organizational_connector_id');
    }

    public function matchingStrategy(): MorphOne
    {
        return $this->morphOne(MatchingStrategy::class, 'matchable');
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function scopeStatuses($query, $statuses)
    {
        $method = 'where';

        foreach ($statuses as $status) {
            if ($status === EngagementSignUpStatus::Open->value) {
                $query->$method('signup_by_date', '>', Carbon::now());
            } elseif ($status === EngagementSignUpStatus::Closed->value) {
                $query->$method('signup_by_date', '<', Carbon::now());
            }
            $method = 'orWhere';
        }

        return $query;
    }

    public function scopeFormats($query, $formats)
    {
        $method = 'where';

        foreach ($formats as $format) {

            $query->$method('format', $format);

            $method = 'orWhere';
        }

        return $query;
    }

    public function scopeSeekings($query, $seekings)
    {
        $method = 'where';

        foreach ($seekings as $seeking) {
            if ($seeking === SeekingForEngagement::Participants->value) {
                $query->$method('recruitment', 'open-call');
            } elseif ($seeking === SeekingForEngagement::Connectors->value) {
                $query->$method(function (Builder $engagementQuery) {
                    $engagementQuery->withExtraAttributes('seeking_community_connector', true);
                });
            } elseif ($seeking === SeekingForEngagement::Organizations->value) {
                $query->$method('who', 'organization');
            }
            $method = 'orWhere';
        }

        return $query;
    }

    public function scopeInitiators($query, $initiators)
    {
        $method = 'whereHas';

        foreach ($initiators as $initiator) {
            if ($initiator === ProjectInitiator::Organization->value) {
                $query->$method('project', function (Builder $projectQuery) {
                    $projectQuery->where('projectable_type', 'App\Models\Organization');
                });
            } elseif ($initiator === ProjectInitiator::RegulatedOrganization->value) {
                $query->$method('project', function (Builder $projectQuery) {
                    $projectQuery->where('projectable_type', 'App\Models\RegulatedOrganization');
                });
            }
            $method = 'orWhereHas';
        }

        return $query;
    }

    public function scopeSeekingDisabilityAndDeafGroups($query, $seekingGroups)
    {
        $method = 'whereHas';

        foreach ($seekingGroups as $seekingGroup) {
            $query->$method('matchingStrategy', function (Builder $matchingStrategyQuery) use ($seekingGroup) {
                $matchingStrategyQuery
                    ->whereHas('identities', function (Builder $criteriaQuery) use ($seekingGroup) {
                        $criteriaQuery->where('identity_id', $seekingGroup);
                    })
                    ->orWhere('cross_disability_and_deaf', true);
            });

            $method = 'orWhereHas';
        }

        return $query;
    }

    public function scopeMeetingTypes($query, $meetingTypes)
    {
        $method = 'where';

        foreach ($meetingTypes as $meetingType) {
            $query->$method(function (Builder $engagementQuery) use ($meetingType) {
                $engagementQuery->whereIn('format', ['interviews', 'workshop', 'focus-group', 'other-sync'])
                    ->whereJsonContains('meeting_types', $meetingType)
                    ->orWhereHas('meetings', function (Builder $meetingQuery) use ($meetingType) {
                        $meetingQuery->whereJsonContains('meeting_types', $meetingType);
                    });
            });

            $method = 'orWhere';
        }

        return $query;
    }

    public function scopeCompensations($query, $compensations)
    {
        $method = 'where';

        foreach ($compensations as $compensation) {
            $query->$method('paid', $compensation === Compensation::Paid->value);

            $method = 'orWhere';
        }

        return $query;
    }

    public function scopeSectors($query, $sectors)
    {
        $method = 'whereHas';

        foreach ($sectors as $sector) {
            $query->$method('project', function (Builder $projectQuery) use ($sector) {
                $projectQuery->whereHas('projectable', function (Builder $projectableQuery) use ($sector) {
                    $projectableQuery->whereHas('sectors', function (Builder $sectorQuery) use ($sector) {
                        $sectorQuery->where('sector_id', $sector);
                    });
                });
            });

            $method = 'orWhereHas';
        }

        return $query;
    }

    public function scopeAreasOfImpact($query, $impacts)
    {
        $method = 'whereHas';

        foreach ($impacts as $impact) {
            $query->$method('project', function (Builder $projectQuery) use ($impact) {
                $projectQuery->whereHas('impacts', function (Builder $impactQuery) use ($impact) {
                    $impactQuery->where('impact_id', $impact);
                });
            });

            $method = 'orWhereHas';
        }

        return $query;
    }

    public function scopeRecruitmentMethods($query, $recruitmentMethods)
    {
        $method = 'where';

        foreach ($recruitmentMethods as $recruitmentMethod) {
            $query->$method('recruitment', $recruitmentMethod);

            $method = 'orWhere';
        }

        return $query;
    }

    public function scopeLocations($query, $locations)
    {
        $method = 'whereHas';

        foreach ($locations as $location) {
            $query->$method('matchingStrategy', function (Builder $matchingStrategyQuery) use ($location) {
                $matchingStrategyQuery->whereJsonContains('regions', $location)
                    ->orWhereJsonContains('locations', ['region' => $location]);
            });

            $method = 'orWhereHas';
        }

        return $query;
    }

    public function scopeActive($query)
    {
        $query->whereHas('project', function (Builder $projectQuery) {
            $projectQuery->where('end_date', '>', now());
        })
            ->where(function (Builder $engagementQuery) {
                $engagementQuery->whereDoesntHave('meetings')
                    ->orWhereHas('meetings', function (Builder $meetingQuery) {
                        $meetingQuery->where('date', '>', now());
                    });
            })
            ->where(function (Builder $engagementQuery) {
                $engagementQuery->whereNull('complete_by_date')
                    ->orWhere('complete_by_date', '>', now());
            })
            ->where(function (Builder $engagementQuery) {
                $engagementQuery->whereNull('window_end_date')
                    ->orWhere('window_end_date', '>', now());
            });

        return $query;
    }

    public function scopeComplete($query)
    {
        $query->whereHas('project', function (Builder $projectQuery) {
            $projectQuery->where('end_date', '<', now());
        })
            ->orWhere(function (Builder $engagementQuery) {
                $engagementQuery->whereHas('meetings')
                    ->whereDoesntHave('meetings', function (Builder $meetingQuery) {
                        $meetingQuery->where('date', '>', now());
                    });
            })
            ->orWhere('complete_by_date', '<', now())
            ->orWhere('window_end_date', '<', now());

        return $query;
    }
}
