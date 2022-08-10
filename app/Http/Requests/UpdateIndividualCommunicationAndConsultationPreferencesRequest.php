<?php

namespace App\Http\Requests;

use App\Enums\MeetingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateIndividualCommunicationAndConsultationPreferencesRequest extends FormRequest
{
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
        $validator->sometimes('preferred_contact_method', 'in:email', function ($input) {
            return $input->preferred_contact_person == 'me' && ! is_null($input->email) && is_null($input->phone) ||
                $input->preferred_contact_person == 'support-person' && ! is_null($input->support_person_email) && is_null($input->support_person_phone);
        });

        $validator->sometimes('preferred_contact_method', 'in:phone', function ($input) {
            return  $input->preferred_contact_person == 'me' && is_null($input->email) && ! is_null($input->phone) ||
                $input->preferred_contact_person == 'support-person' && is_null($input->support_person_email) && ! is_null($input->support_person_phone);
        });
    }

    public function attributes(): array
    {
        return [
            'email' => __('email address'),
            'phone' => __('phone number'),
            'support_person_email' => __('email address'),
            'support_person_phone' => __('phone number'),
        ];
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
        ];
    }
}
