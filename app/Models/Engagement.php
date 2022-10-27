<?php

namespace App\Models;

use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\MeetingType;
use App\Models\Scopes\EngagementProjectableNotSuspendedScope;
use App\Traits\HasSchemalessAttributes;
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
use Spatie\Translatable\HasTranslations;

class Engagement extends Model
{
    use HasFactory;
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
}
