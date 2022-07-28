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
            'contact_person_email' => 'nullable|email|required_without:contact_person_phone',
            'contact_person_phone' => 'nullable|phone:CA|required_if:contact_person_vrs,true|required_without:contact_person_email',
            'contact_person_vrs' => 'nullable|boolean',
            'preferred_contact_method' => 'required|in:email,phone',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('preferred_contact_method', 'in:email', function ($input) {
            return ! $input->contact_person_phone;
        });

        $validator->sometimes('preferred_contact_method', 'in:phone', function ($input) {
            return ! $input->contact_person_email;
        });
    }
}
