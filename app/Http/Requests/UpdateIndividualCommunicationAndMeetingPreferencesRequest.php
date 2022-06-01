<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateIndividualCommunicationAndMeetingPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->individual);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'preferred_contact_person' => 'required|in:me,support-person',
            'email' => 'nullable|email',
            'phone' => 'required_if:vrs,true|nullable|string',
            'vrs' => 'nullable|boolean',
            'support_person_name' => 'required_if:preferred_contact_person,support-person|nullable|string',
            'support_person_email' => 'nullable|email',
            'support_person_phone' => 'required_if:support_person_vrs,true|nullable|string',
            'support_person_vrs' => 'nullable|boolean',
            'preferred_contact_method' => 'required|in:email,phone',
            'meeting_types' => 'required|array|min:1|in:in_person,web_conference,phone',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
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
