<?php

namespace App\Models;

use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Makeable\EloquentStatus\HasStatus;
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
        'start_date',
        'end_date',
        'timezone',
        'weekday_availabilities',
        'document_languages',
        'accepted_formats',
        'individual_connector_id',
        'organizational_connector_id',
        'individual_consultant_id',
        'organizational_consultant_id',
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
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'weekday_availabilities' => 'array',
        'document_languages' => 'array',
        'accepted_formats' => 'array',
        'directions' => 'array',
        'additional_video_information' => 'array',
        'additional_phone_information' => 'array',
        'other_accepted_format' => 'array',
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

    public function hasEstimateAndAgreement(): bool
    {
        return $this->project->checkStatus('estimateApproved') && $this->project->checkStatus('agreementReceived');
    }

    public function isPublishable(): bool
    {
        return ! is_null($this->signup_by_date);
    }

    public function scopeWherePublishable(Builder $query): Builder
    {
        return $query->whereNotNull('signup_by_date');
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
        return $this->belongsToMany(Individual::class)->withPivot('status');
    }

    public function confirmedParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class)->wherePivot('status', 'confirmed');
    }

    public function organizationalParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)->withPivot('status');
    }

    public function confirmedOrganizationalParticipants(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)->wherePivot('status', 'confirmed');
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
}
