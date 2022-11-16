<?php

namespace App\Http\Requests;

use App\Enums\MeetingType;
use App\Traits\ConditionallyRequireContactMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateIndividualCommunicationAndConsultationPreferencesRequest extends FormRequest
{
    use ConditionallyRequireContactMethods;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->individual);
    }

    public function rules(): array
    {
        return [
            'preferred_contact_person' => 'required|in:me,support-person',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'phone' => 'required_if:vrs,true|nullable|phone:CA',
            'vrs' => 'nullable|boolean',
            'support_person_name' => 'required_if:preferred_contact_person,support-person|nullable|string|exclude_if:preferred_contact_person,me',
            'support_person_email' => 'nullable|string|email|max:255',
            'support_person_phone' => 'required_if:support_person_vrs,true|nullable|phone:CA|exclude_if:preferred_contact_person,me',
            'support_person_vrs' => 'nullable|boolean|exclude_if:preferred_contact_person,me',
            'preferred_contact_method' => 'required|in:email,phone',
            'meeting_types' => 'required|array',
            'meeting_types.*' => [new Enum(MeetingType::class)],
        ];
    }

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'meeting_types' => [],
        ]);
    }

    public function withValidator(Validator $validator)
    {
        $this->conditionallyRequireContactMethods($validator);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'support_person_name.required_if' => __('Your support person’s name is required if they are your preferred contact person.'),
            'phone.required_if' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.'),
            'support_person_phone.required_if' => __('Since you have indicated that your support person needs VRS, please enter a phone number.'),
        ];
    }
}
