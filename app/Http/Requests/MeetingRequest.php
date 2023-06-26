<?php

namespace App\Http\Requests;

use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Worksome\RequestFactories\Concerns\HasFactory;

class MeetingRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $inPerson = MeetingType::InPerson->value;
        $webConference = MeetingType::WebConference->value;
        $phone = MeetingType::Phone->value;

        return [
            'title.en' => 'required_without:title.fr',
            'title.fr' => 'required_without:title.en',
            'title.*' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:G:i|before:end_time',
            'end_time' => 'required|date_format:G:i|after:start_time',
            'timezone' => 'required|timezone',
            'meeting_types' => 'required|array',
            'meeting_types.*' => [
                'nullable',
                new Enum(MeetingType::class),
            ],
            'street_address' => [
                'nullable',
                Rule::excludeIf(! in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf(in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'unit_suite_floor' => [
                'nullable',
                Rule::excludeIf(! in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'locality' => [
                'nullable',
                Rule::excludeIf(! in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf(in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'region' => [
                'nullable',
                Rule::excludeIf(! in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf(in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                new Enum(ProvinceOrTerritory::class),
            ],
            'postal_code' => [
                'nullable',
                Rule::excludeIf(! in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf(in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'postal_code:CA',
            ],
            'directions' => [
                'nullable',
                Rule::excludeIf(! in_array($inPerson, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'array',
            ],
            'meeting_software' => [
                'nullable',
                Rule::excludeIf(! in_array($webConference, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf(in_array($webConference, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'alternative_meeting_software' => [
                'nullable',
                Rule::excludeIf(! in_array($webConference, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'boolean',
            ],
            'meeting_url' => [
                'nullable',
                Rule::excludeIf(! in_array($webConference, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf(in_array($webConference, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'url',
            ],
            'additional_video_information' => [
                'nullable',
                Rule::excludeIf(! in_array($webConference, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'array',
            ],
            'meeting_phone' => [
                'nullable',
                Rule::excludeIf(! in_array($phone, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf(in_array($phone, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'phone:CA,US',
            ],
            'additional_phone_information' => [
                'nullable',
                Rule::excludeIf(! in_array($phone, is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
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
            'meeting_types.*.Illuminate\Validation\Rules\Enum' => __('You must select a valid meeting type.'),
            'street_address.required' => __('You must enter a :attribute for the meeting location.'),
            'locality.required' => __('You must enter a :attribute for the meeting location.'),
            'region.required' => __('You must enter a :attribute for the meeting location.'),
            'postal_code.required' => __('You must enter a :attribute for the meeting location.'),
            'meeting_software.required' => __('You must indicate the :attribute.'),
            'meeting_url.required' => __('You must provide a :attribute.'),
            'start_time.date_format' => __('The :attribute format is not valid.'),
            'end_time.date_format' => __('The :attribute format is not valid.'),
        ];
    }
}
