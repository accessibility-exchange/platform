<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationContactInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_person_name' => 'required|string',
            'contact_person_email' => 'nullable|email|required_without:contact_person_phone|required_if:preferred_contact_method,email',
            'contact_person_phone' => 'nullable|phone:CA|required_without:contact_person_email|required_if:preferred_contact_method,phone',
            'contact_person_vrs' => 'nullable|boolean',
            'preferred_contact_method' => 'required|in:email,phone',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->contact_person_vrs && ! $this->contact_person_phone) {
                $validator->errors()->add(
                    'contact_person_phone',
                    __('Since you have indicated that your contact person needs VRS, please enter a phone number.')
                );
            }
        });
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'contact_person_vrs' => null,
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function attributes(): array
    {
        return [
            'contact_person_email' => 'email address',
            'contact_person_phone' => 'phone number',
        ];
    }
}
