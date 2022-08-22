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
            'other_disability_type' => 'nullable|array||exclude_if:base_disability_type,cross_disability|exclude_unless:other_disability,true',
            'other_disability_type.*' => 'nullable|string|max:255',
            'area_types' => 'required|array|min:1',
            'area_types.*' => 'exists:area_types,id',
            'has_indigenous_identities' => 'required|boolean',
            'indigenous_identities' => 'nullable|array|required_if:has_indigenous_identities,true|exclude_if:has_indigenous_identities,false',
            'indigenous_identities.*' => 'exists:indigenous_identities,id',
            'refugees_and_immigrants' => 'sometimes|boolean',
            'has_gender_and_sexual_identities' => 'required|boolean',
            'gender_and_sexual_identities' => 'nullable|array|min:1|required_if:has_gender_and_sexual_identities,true|exclude_if:has_gender_and_sexual_identities,false',
            'gender_and_sexual_identities.*' => 'string|in:women,nb-gnc-fluid-people,trans-people,2slgbtqiaplus-people',
            'has_age_brackets' => 'required|boolean',
            'age_brackets' => 'nullable|array|required_if:has_age_brackets,true|exclude_if:has_age_brackets,false',
            'age_brackets.*' => 'exists:age_brackets,id',
            'has_ethnoracial_identities' => 'required|boolean',
            'ethnoracial_identities' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('has_ethnoracial_identities') == 1
                        && ! request('other_ethnoracial');
                }),
            ],
            'ethnoracial_identities.*' => 'exists:ethnoracial_identities,id',
            'other_ethnoracial' => 'nullable|boolean',
            'other_ethnoracial_identity' => 'nullable|array|exclude_if:has_ethnoracial_identities,false|exclude_unless:other_ethnoracial,true',
            'other_ethnoracial_identity.*' => 'nullable|string|max:255',
            'constituent_languages' => 'nullable|array',
            'constituent_languages.*' => [Rule::in(array_keys(get_available_languages(true)))],
            'staff_lived_experience' => 'required|string|in:yes,no,prefer-not-to-answer',
        ];
    }

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'lived_experiences' => [],
        ]);
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
