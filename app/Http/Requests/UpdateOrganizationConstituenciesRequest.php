<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationConstituenciesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lived_experiences' => 'required|array',
            'lived_experiences.*' => 'exists:lived_experiences,id',
            'base_disability_type' => [
                'nullable',
                'string',
                'in:cross_disability,specific_disabilities',
                Rule::requiredIf(function () {
                    return in_array(1, request('lived_experiences') ?? []);
                }),
            ],
            'disability_types' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return
                        in_array(1, request('lived_experiences') ?? [])
                        && request('base_disability_type') === 'specific_disabilities'
                        && ! request('other_disability_type');
                }),
                'exclude_if:base_disability_type,cross_disability',
            ],
            'disability_types.*' => 'exists:disability_types,id',
            'other_disability' => 'nullable|boolean',
            'other_disability_type' => 'nullable|array||exclude_if:base_disability_type,cross_disability|exclude_unless:other_disability,1',
            'other_disability_type.*' => 'nullable|string|max:255',
            'area_types' => 'required|array|min:1',
            'area_types.*' => 'exists:area_types,id',
            'indigenous_identities' => 'nullable|array|required_if:has_indigenous_identities,1|exclude_if:has_indigenous_identities,0',
            'indigenous_identities.*' => 'exists:indigenous_identities,id',
            'refugees_and_immigrants' => 'sometimes|boolean',
            'gender_and_sexual_identities' => [
                'nullable',
                'array',
                'min:1',
                Rule::requiredIf(function () {
                    return request('has_gender_and_sexual_identities') == 1;
                }),
                'exclude_if:has_gender_and_sexual_identities,0',
            ],
            'gender_and_sexual_identities.*' => 'string|in:women,nb-gnc-fluid-people,trans-people,2slgbtqiaplus-people',
            'age_brackets' => 'nullable|array|required_if:has_age_brackets,1|exclude_if:has_age_brackets,0',
            'age_brackets.*' => 'exists:age_brackets,id',
            'ethnoracial_identities' => 'nullable|array|required_if:has_ethnoracial_identities,1|exclude_if:has_ethnoracial_identities,0',
            'ethnoracial_identities.*' => 'exists:ethnoracial_identities,id',
            'staff_lived_experience' => 'required|string|in:yes,no,prefer-not-to-answer',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('other_disability_type.en', 'required_without:other_disability_type.fr', function ($input) {
            return $input->other_disability;
        });

        $validator->sometimes('other_disability_type.fr', 'required_without:other_disability_type.en', function ($input) {
            return $input->other_disability;
        });
    }

    public function messages(): array
    {
        return [
            'disability_types.required' => __('You must select at least one disability type.'),
            'indigenous_identities.required_if' => __('You must select at least one Indigenous identity.'),
            'gender_identities.required' => __('You must select at least one gender or sexual identity.'),
            'trans_people.required' => __('You must select at least one gender or sexual identity.'),
            'twoslgbtqia.required' => __('You must select at least one gender or sexual identity.'),
        ];
    }
}
