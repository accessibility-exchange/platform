<?php

namespace App\Http\Requests;

use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class MeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title.*' => 'nullable|string',
            'title.en' => 'required_without:title.fr',
            'title.fr' => 'required_without:title.en',
            'date' => 'required|date',
            'start_time' => 'required|date_format:G:i',
            'end_time' => 'required|date_format:G:i',
            'timezone' => 'required|timezone',
            'meeting_types' => 'required|array',
            'meeting_types.*' => [
                'nullable',
                new Enum(MeetingType::class),
            ],
            'street_address' => [
                'nullable',
                Rule::excludeIf(! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf(in_array('in_person', $this->input('meeting_types', []))),
                'string',
            ],
            'unit_suite_floor' => [
                'nullable',
                Rule::excludeIf(! in_array('in_person', $this->input('meeting_types', []))),
                'string',
            ],
            'locality' => [
                'nullable',
                Rule::excludeIf(! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf(in_array('in_person', $this->input('meeting_types', []))),
                'string',
            ],
            'region' => [
                'nullable',
                Rule::excludeIf(! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf(in_array('in_person', $this->input('meeting_types', []))),
                new Enum(ProvinceOrTerritory::class),
            ],
            'postal_code' => [
                'nullable',
                Rule::excludeIf(! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf(in_array('in_person', $this->input('meeting_types', []))),
                'postal_code:CA',
            ],
            'directions' => [
                'nullable',
                Rule::excludeIf(! in_array('in_person', $this->input('meeting_types', []))),
                'array',
            ],
            'meeting_software' => [
                'nullable',
                Rule::excludeIf(! in_array('web_conference', $this->input('meeting_types', []))),
                Rule::requiredIf(in_array('web_conference', $this->input('meeting_types', []))),
            ],
            'alternative_meeting_software' => [
                'nullable',
                Rule::excludeIf(! in_array('web_conference', $this->input('meeting_types', []))),
                'boolean',
            ],
            'meeting_url' => [
                'nullable',
                Rule::excludeIf(! in_array('web_conference', $this->input('meeting_types', []))),
                Rule::requiredIf(in_array('web_conference', $this->input('meeting_types', []))),
            ],
            'additional_video_information' => [
                'nullable',
                Rule::excludeIf(! in_array('web_conference', $this->input('meeting_types', []))),
                'array',
            ],
            'meeting_phone' => [
                'nullable',
                Rule::excludeIf(! in_array('phone', $this->input('meeting_types', []))),
                Rule::requiredIf(in_array('phone', $this->input('meeting_types', []))),
                'phone:CA',
            ],
            'additional_phone_information' => [
                'nullable',
                Rule::excludeIf(! in_array('phone', $this->input('meeting_types', []))),
                'array',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'start_time' => __('meeting start time'),
            'end_time' => __('meeting end time'),
            'date' => __('meeting date'),
            'timezone' => __('meeting time zone'),
            'region' => __('province or territory'),
            'meeting_url' => __('link to join the meeting'),
            'meeting_phone' => __('meeting phone number'),
        ];
    }

    public function messages(): array
    {
        return [
            'title.*.required_without' => __('A meeting title must be provided in at least one language.'),
            'meeting_types.required' => __('You must indicate at least one way for participants to attend the meeting.'),
            'street_address.required' => __('You must enter a :attribute for the meeting location.'),
            'locality.required' => __('You must enter a :attribute for the meeting location.'),
            'region.required' => __('You must enter a :attribute for the meeting location.'),
            'postal_code.required' => __('You must enter a :attribute for the meeting location.'),
            'meeting_software.required' => __('You must indicate the :attribute.'),
            'meeting_url.required' => __('You must provide a :attribute.'),
        ];
    }
}
