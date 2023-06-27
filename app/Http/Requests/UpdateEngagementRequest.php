<?php

namespace App\Http\Requests;

use App\Enums\AcceptedFormat;
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
        $weekdayAvailabilitiesRules = [
            'nullable',
            Rule::excludeIf($this->engagement->format !== 'interviews'),
            Rule::requiredIf($this->engagement->format === 'interviews'),
            new Enum(Availability::class),
        ];

        return [
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'name.*' => 'nullable|string',
            'description.en' => 'required_without:description.fr',
            'description.fr' => 'required_without:description.en',
            'description.*' => 'nullable|string',
            'window_start_date' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date',
                'before:window_end_date',
            ],
            'window_end_date' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date',
                'after:window_start_date',
            ],
            'window_start_time' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date_format:G:i',
                'before:window_end_time',
            ],
            'window_end_time' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date_format:G:i',
                'after:window_start_time',
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
            'weekday_availabilities.monday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.tuesday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.wednesday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.thursday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.friday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.saturday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.sunday' => $weekdayAvailabilitiesRules,
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
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'unit_suite_floor' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'locality' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'region' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                new Enum(ProvinceOrTerritory::class),
            ],
            'postal_code' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'postal_code:CA',
            ],
            'directions' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'array',
            ],
            'meeting_software' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('web_conference', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'string',
            ],
            'alternative_meeting_software' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'boolean',
            ],
            'meeting_url' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('web_conference', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'url',
            ],
            'additional_video_information' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'array',
            ],
            'meeting_phone' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('phone', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('phone', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'phone:CA,US',
            ],
            'additional_phone_information' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('phone', is_array($this->input('meeting_types')) ? $this->input('meeting_types') : [])),
                'array',
            ],
            'materials_by_date' => [
                'nullable',
                Rule::excludeIf(! in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                Rule::requiredIf(in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                'date',
                'before:complete_by_date',
            ],
            'complete_by_date' => [
                'nullable',
                Rule::excludeIf(! in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                Rule::requiredIf(in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                'date',
                'after:materials_by_date',
            ],
            'document_languages' => [
                'nullable',
                Rule::requiredIf(in_array($this->engagement->format, ['survey', 'other-async'])),
                'array',
            ],
            'document_languages.*' => [
                Rule::in(array_keys(get_available_languages(true))),
            ],
            'accepted_formats' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews' && ! request('other_accepted_format')),
                'array',
            ],
            'accepted_formats.*' => [
                'nullable',
                new Enum(AcceptedFormat::class),
            ],
            'other_accepted_formats' => [
                'nullable',
                'boolean',
            ],
            'other_accepted_format' => [
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! request('other_accepted_formats')),
            ],
            'other_accepted_format.en' => [
                'nullable',
                'required_without:name.fr',
                'string',
            ],
            'other_accepted_format.fr' => [
                'nullable',
                'required_without:name.en',
                'string',
            ],
            'open_to_other_formats' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                'boolean',
            ],
            'paid' => [
                'nullable',
                'boolean',
            ],
            'signup_by_date' => [
                'nullable',
                Rule::requiredIf($this->engagement->who === 'individuals'),
                Rule::excludeIf($this->engagement->who === 'organization'),
                'date',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('other_accepted_format.en', 'required_without:other_accepted_format.fr', function ($input) {
            return $input->other_accepted_formats === false;
        });

        $validator->sometimes('other_accepted_format.fr', 'required_without:other_accepted_format.en', function ($input) {
            return ! $input->other_accepted_formats === false;
        });

        $validator->sometimes('signup_by_date', 'before:window_start_date', function ($input) {
            return ! blank($input->window_start_date);
        });

        $validator->sometimes('signup_by_date', 'before:materials_by_date', function ($input) {
            return ! blank($input->materials_by_date);
        });
    }

    public function prepareForValidation(): void
    {
        $fallbacks = [
            'accepted_formats' => [],
            'other_accepted_formats' => false,
            'other_accepted_format' => [],
            'paid' => true,
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks)->merge([
            'meeting_url' => normalize_url($this->meeting_url),
        ]);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
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
            'meeting_phone' => __('phone number to join the meeting'),
            'materials_by_date' => __('date for materials to be sent by'),
            'complete_by_date' => __('due date'),
            'signup_by_date' => __('sign up deadline'),
            'other_accepted_formats' => __('accepted formats'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.required_without' => __('An engagement name must be provided in at least English or French.'),
            'description.*.required_without' => __('An engagement description must be provided in at least English or French.'),
            'document_languages.required' => __('Please select a language that the engagement documents will be in.'),
            'document_languages.*.in' => __('Please select a language that the engagement documents will be in.'),
            'window_start_date.required' => __('You must enter a :attribute'),
            'window_start_date.before' => __('The :attribute must be before the :date.'),
            'window_end_date.required' => __('You must enter a :attribute'),
            'window_end_date.after' => __('The :attribute must be after the :date.'),
            'window_start_time.date_format' => __('The :attribute is not in the right format.'),
            'window_start_time.required' => __('You must enter a :attribute'),
            'window_start_time.before' => __('The :attribute must be before the :date.'),
            'window_end_time.date_format' => __('The :attribute is not in the right format.'),
            'window_end_time.required' => __('You must enter a :attribute'),
            'window_end_time.after' => __('The :attribute must be after the :date.'),
            'timezone.required' => __('You must enter a :attribute'),
            'timezone.timezone' => __('You must enter a :attribute'),
            'street_address.required' => __('You must enter a :attribute for the meeting location.'),
            'locality.required' => __('You must enter a :attribute for the meeting location.'),
            'region.required' => __('You must enter a :attribute for the meeting location.'),
            'postal_code.required' => __('You must enter a :attribute for the meeting location.'),
            'meeting_types.required' => __('You must select at least one way to attend the meeting.'),
            'meeting_software.required' => __('You must indicate the :attribute.'),
            'meeting_url.required' => __('You must enter a :attribute.'),
            'accepted_formats.required' => __('You must indicate the :attribute.'),
            'accepted_formats.*.Illuminate\Validation\Rules\Enum' => __('You must select a valid format.'),
            'other_accepted_formats.required' => __('You must indicate the :attribute.'),
            'other_accepted_format.*.string' => __('The other accepted format must be a string.'),
            'other_accepted_format.*.required_without' => __('The other accepted format must be provided in at least English or French.'),
            'meeting_types.*.Illuminate\Validation\Rules\Enum' => __('You must select a valid meeting type.'),
            'materials_by_date.required' => __('You must enter a :attribute.'),
            'materials_by_date.date' => __('Please enter a valid :attribute.'),
            'materials_by_date.before' => __('The :attribute must be before the :date.'),
            'complete_by_date.required' => __('You must enter a :attribute.'),
            'complete_by_date.date' => __('Please enter a valid :attribute.'),
            'complete_by_date.after' => __('The :attribute must be after the :date.'),
            'signup_by_date' => __('You must enter a :attribute.'),
            'signup_by_date.date' => __('Please enter a valid date for the :attribute.'),
            'signup_by_date.before' => __('The :attribute must be before the :date.'),
            'signup_by_date.before' => __('The :attribute must be before the :date.'),
        ];
    }
}
