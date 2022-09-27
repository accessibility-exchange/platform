<?php

namespace App\Http\Requests;

use App\Enums\Availability;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateEngagementRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->engagement);
    }

    public function rules(): array
    {
        $weekday_availabilities_rules = [
            'nullable',
            Rule::excludeIf($this->engagement->format !== 'interviews'),
            Rule::requiredIf($this->engagement->format === 'interviews'),
            new Enum(Availability::class),
        ];

        return [
            'name.*' => 'nullable|string',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'description.*' => 'nullable|string',
            'description.en' => 'required_without:description.fr',
            'description.fr' => 'required_without:description.en',
            'window_start_date' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date',
            ],
            'window_end_date' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date',
            ],
            'window_start_time' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date_format:G:i',
            ],
            'window_end_time' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date_format:G:i',
            ],
            'timezone' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'timezone',
            ],
            'window_flexibility' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                'boolean',
            ],
            'weekday_availabilities' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'array',
            ],
            'weekday_availabilities.monday' => $weekday_availabilities_rules,
            'weekday_availabilities.tuesday' => $weekday_availabilities_rules,
            'weekday_availabilities.wednesday' => $weekday_availabilities_rules,
            'weekday_availabilities.thursday' => $weekday_availabilities_rules,
            'weekday_availabilities.friday' => $weekday_availabilities_rules,
            'weekday_availabilities.saturday' => $weekday_availabilities_rules,
            'weekday_availabilities.sunday' => $weekday_availabilities_rules,
            'meeting_types' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'array',
            ],
            'meeting_types.*' => [
                'nullable',
                new Enum(MeetingType::class),
            ],
            'street_address' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', request('meeting_types'))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', request('meeting_types'))),
                'string',
            ],
            'unit_suite_floor' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', request('meeting_types'))),
                'string',
            ],
            'locality' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', request('meeting_types'))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', request('meeting_types'))),
                'string',
            ],
            'region' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', request('meeting_types'))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', request('meeting_types'))),
                new Enum(ProvinceOrTerritory::class),
            ],
            'postal_code' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', request('meeting_types'))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', request('meeting_types'))),
                'postal_code:CA',
            ],
            'directions' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', request('meeting_types'))),
                'array',
            ],
            'meeting_software' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', request('meeting_types'))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('web_conference', request('meeting_types'))),
            ],
            'alternative_meeting_software' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', request('meeting_types'))),
                'boolean',
            ],
            'meeting_url' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', request('meeting_types'))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('web_conference', request('meeting_types'))),
            ],
            'additional_video_information' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', request('meeting_types'))),
                'array',
            ],
            'meeting_phone' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('phone', request('meeting_types'))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('phone', request('meeting_types'))),
                'phone:CA',
            ],
            'additional_phone_information' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('phone', request('meeting_types'))),
                'array',
            ],
            'materials_by_date' => [
                'nullable',
                Rule::excludeIf(! in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                Rule::requiredIf(in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                'date',
            ],
            'complete_by_date' => [
                'nullable',
                Rule::excludeIf(! in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                Rule::requiredIf(in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                'date',
            ],
            'accepted_formats' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'array',
            ],
            'accepted_formats.*' => [
                'nullable',
                'in_array:writing,audio,video',
            ],
            'signup_by_date' => 'required|date',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'meeting_url' => normalize_url($this->meeting_url),
        ]);
    }

    public function attributes(): array
    {
        return [
            'window_start_date' => __('start date'),
            'window_end_date' => __('end date'),
            'window_start_time' => __('start time'),
            'window_end_time' => __('end time'),
            'weekday_availabilities.monday' => __('availability for Monday'),
            'weekday_availabilities.tuesday' => __('availability for Tuesday'),
            'weekday_availabilities.wednesday' => __('availability for Wednesday'),
            'weekday_availabilities.thursday' => __('availability for Thursday'),
            'weekday_availabilities.friday' => __('availability for Friday'),
            'weekday_availabilities.saturday' => __('availability for Saturday'),
            'weekday_availabilities.sunday' => __('availability for Sunday'),
            'locality' => __('city or town'),
            'region' => __('province or territory'),
            'meeting_url' => __('link to join the meeting'),
            'materials_by_date' => __('sent by date'),
            'complete_by_date' => __('due date'),
            'signup_by_date' => __('sign up deadline'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.required_without' => __('An engagement name must be provided in at least one language.'),
            'description.*.required_without' => __('An engagement description must be provided in at least one language.'),
            'window_start_time.date_format' => __('The :attribute is not in the right format.'),
            'window_end_time.date_format' => __('The :attribute is not in the right format.'),
            'street_address.required' => __('You must enter a :attribute for the meeting location.'),
            'locality.required' => __('You must enter a :attribute for the meeting location.'),
            'region.required' => __('You must enter a :attribute for the meeting location.'),
            'postal_code.required' => __('You must enter a :attribute for the meeting location.'),
            'meeting_software.required' => __('You must indicate the :attribute.'),
            'meeting_url.required' => __('You must provide a :attribute.'),
            'accepted_formats.required' => __('You must indicate the :attribute.'),
        ];
    }
}
