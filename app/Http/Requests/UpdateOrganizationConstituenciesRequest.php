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
                        && ! request('other_disability');
                }),
                'exclude_if:base_disability_type,cross_disability',
            ],
            'disability_types.*' => 'exists:disability_types,id',
            'other_disability' => 'nullable|boolean',
            'other_disability_type' => 'nullable|array||exclude_if:base_disability_type,cross_disability|exclude_unless:other_disability,true',
            'other_disability_type.*' => 'nullable|string|max:255',
            'has_indigenous_identities' => 'required|boolean',
            'indigenous_identities' => 'nullable|array|required_if:has_indigenous_identities,true|exclude_if:has_indigenous_identities,false',
            'indigenous_identities.*' => 'exists:indigenous_identities,id',
            'refugees_and_immigrants' => 'required|boolean',
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
            'area_types' => 'required|array|min:1',
            'area_types.*' => 'exists:area_types,id',
            'staff_lived_experience' => 'required|string|in:yes,no,prefer-not-to-answer',
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'lived_experiences' => [],
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function withValidator($validator)
    {
        $validator->sometimes('other_disability_type.en', 'required_without:other_disability_type.fr', function ($input) {
            return $input->other_disability;
        });

        $validator->sometimes('other_disability_type.fr', 'required_without:other_disability_type.en', function ($input) {
            return $input->other_disability;
        });

        $validator->sometimes('other_ethnoracial_identity.en', 'required_without:other_disability_type.fr', function ($input) {
            return $input->other_ethnoracial;
        });

        $validator->sometimes('other_ethnoracial_identity.fr', 'required_without:other_disability_type.en', function ($input) {
            return $input->other_ethnoracial;
        });
    }

    public function messages(): array
    {
        return [
            'lived_experiences.required' => __('You must select at least one option for "Do you specifically :represent_or_serve_and_support people with disabilities, Deaf persons, and/or their supporters?"', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'base_disability_type.required' => __('You must select one option for “Please select people with disabilities that you specifically :represent_or_serve_and_support”.', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'disability_types.required' => __('You must select which specific disability groups your organization :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'area_types.required' => __('You must select at least one option for “Where do the people that you :represent_or_serve_and_support come from?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'has_indigenous_identities.required' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support people who are First Nations, Inuit, or Métis?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'indigenous_identities.required_if' => __('You must select at least one Indigenous group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'refugees_and_immigrants' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support refugees and/or immigrants?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'has_gender_and_sexual_identities.required' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support people who are marginalized based on gender or sexual identity?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'gender_and_sexual_identities.required_if' => __('You must select at least one gender or sexual identity group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'has_age_brackets.required' => __('You must select one option for “Does your organization :represent_or_serve_and_support a specific age bracket or brackets?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'age_brackets.required_if' => __('You must select at least one age group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'has_ethnoracial_identities.required' => __('You must select one option for “Does your organization :represent_or_serve_and_support a specific ethnoracial identity or identities?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'ethnoracial_identities.required' => __('You must select at least one ethno-racial identity your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'constituent_languages.*.in' => __('You must select a language.'),
            'staff_lived_experience.required' => __('You must select one option for “Do you have staff who have lived experience of the people you :represent_or_serve_and_support?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'other_disability_type.*.required_without' => __('There is no disability type filled in under "something else". Please fill this in.'),
            'other_ethnoracial_identity.*.required_without' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
        ];
    }
}
