<?php

namespace App\Http\Requests;

use App\Enums\ContactPerson;
use App\Enums\EngagementFormat;
use App\Enums\MeetingType;
use App\Rules\UniqueUserEmail;
use App\Traits\ConditionallyRequireContactMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateCommunicationAndConsultationPreferencesRequest extends FormRequest
{
    use ConditionallyRequireContactMethods;

    public function authorize(): bool
    {
        return $this->user()->context == 'individual';
    }

    public function rules(): array
    {
        return [
            'preferred_contact_person' => [
                'required',
                new Enum(ContactPerson::class),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                new UniqueUserEmail($this->user()->id),
            ],
            'phone' => 'required_if:vrs,true|nullable|phone:CA',
            'vrs' => 'nullable|boolean',
            'support_person_name' => 'required_if:preferred_contact_person,support-person|nullable|string|exclude_if:preferred_contact_person,me',
            'support_person_email' => 'nullable|string|email|max:255',
            'support_person_phone' => 'required_if:support_person_vrs,true|nullable|phone:CA|exclude_if:preferred_contact_person,me',
            'support_person_vrs' => 'nullable|boolean|exclude_if:preferred_contact_person,me',
            'preferred_contact_method' => 'required|in:email,phone',
            'consulting_methods' => [
                'nullable',
                'array',
                Rule::requiredIf(request()->user()->individual->isParticipant()),
            ],
            'consulting_methods.*' => [new Enum(EngagementFormat::class)],
            'meeting_types' => 'nullable|array',
            'meeting_types.*' => [new Enum(MeetingType::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'preferred_contact_person' => __('preferred contact person'),
            'email' => __('email'),
            'phone' => __('phone'),
            'vrs' => __('vrs'),
            'support_person_name' => __('support person’s name'),
            'support_person_email' => __('support person’s email'),
            'support_person_phone' => __('support person’s phone number'),
            'support_person_vrs' => __('support person requires Video Relay Service (VRS) for phone calls'),
            'preferred_contact_method' => __('preferred contact method'),
            'consulting_methods' => __('consulting methods'),
            'consulting_methods.*' => __('consulting methods'),
            'meeting_types' => __('meeting types'),
            'meeting_types.*' => __('meeting types'),
        ];
    }

    public function withValidator(Validator $validator)
    {
        $this->conditionallyRequireContactMethods($validator);

        $validator->sometimes('meeting_types', 'required', function ($input) {
            return $input->consulting_methods && array_intersect(['interviews', 'focus-group', 'workshop'], $input->consulting_methods);
        });
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'consulting_methods' => [],
            'meeting_types' => [],
            'support_person_vrs' => null,
            'vrs' => null,
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function messages(): array
    {
        return [
            'support_person_name.required_if' => __('Your support person’s name is required if they are your preferred contact person.'),
            'phone.required_if' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.'),
            'support_person_phone.required_if' => __('Since you have indicated that your support person needs VRS, please enter a phone number.'),
        ];
    }
}
