<?php

namespace App\Models;

use App\Enums\MeetingType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\Translatable\HasTranslations;

class Meeting extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'engagement_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'timezone',
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
    ];

    protected $casts = [
        'title' => 'array',
        'date' => 'datetime:Y-m-d',
        'start_time' => 'datetime:G:i',
        'end_time' => 'datetime:G:i',
        'meeting_types' => 'array',
        'directions' => 'array',
        'alternative_meeting_software' => 'boolean',
        'additional_video_information' => 'array',
        'meeting_phone' => E164PhoneNumberCast::class.':CA',
        'additional_phone_information' => 'array',
    ];

    protected $translatable = [
        'title',
        'directions',
        'additional_video_information',
        'additional_phone_information',
    ];

    public function engagement(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function displayMeetingTypes(): Attribute
    {
        return Attribute::make(
            get: fn () => Arr::map($this->meeting_types, fn ($meeting_type) => MeetingType::labels()[$meeting_type])
        );
    }

    public function start(): Attribute
    {
        return Attribute::make(
            get: fn () => new Carbon($this->date->toDateString().'T'.$this->start_time->toTimeString(), $this->timezone),
        );
    }

    public function end(): Attribute
    {
        return Attribute::make(
            get: fn () => new Carbon($this->date->toDateString().'T'.$this->end_time->toTimeString(), $this->timezone),
        );
    }
}
