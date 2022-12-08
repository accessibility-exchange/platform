<?php

namespace App\Http\Requests;

use App\Enums\BaseDisabilityType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateOrganizationConstituenciesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'disability_and_deaf' => 'nullable|boolean|required_without:lived_experience_constituencies',
            'lived_experience_constituencies' => 'nullable|array|required_if:disability_and_deaf,false',
            'lived_experience_constituencies.*' => 'exists:identities,id',
            'base_disability_type' => [
                'nullable',
                'required_if:disability_and_deaf,true',
                'exclude_if:disability_and_deaf,false',
                'exclude_without:disability_and_deaf',
                new Enum(BaseDisabilityType::class),
            ],
            'disability_and_deaf_constituencies' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('base_disability_type') === 'specific_disabilities'
                        && ! request('has_other_disability_constituency');
                }),
                'exclude_if:disability_and_deaf,false',
                'exclude_without:disability_and_deaf',
                'exclude_if:base_disability_type,cross_disability_and_deaf',
                'exclude_without:base_disability_type',
            ],
            'disability_and_deaf_constituencies.*' => 'exists:identities,id',
            'has_other_disability_constituency' => 'nullable|boolean',
            'other_disability_constituency' => 'nullable|array|exclude_if:base_disability_type,cross_disability|exclude_unless:has_other_disability_constituency,true',
            'other_disability_constituency.*' => 'nullable|string|max:255',
            'has_indigenous_constituencies' => 'required|boolean',
            'indigenous_constituencies' => 'nullable|array|required_if:has_indigenous_constituencies,true|exclude_if:has_indigenous_constituencies,false',
            'indigenous_constituencies.*' => 'exists:identities,id',
            'refugees_and_immigrants' => 'required|boolean',
            'has_gender_and_sexuality_constituencies' => 'required|boolean',
            'gender_and_sexuality_constituencies' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('has_gender_and_sexuality_constituencies')
                        && ! request('nb_gnc_fluid_identity');
                }),
                'exclude_if:has_gender_and_sexuality_constituencies,false',
            ],
            'gender_and_sexuality_constituencies.*' => 'exists:identities,id',
            'nb_gnc_fluid_identity' => [
                'nullable',
                'boolean',
                Rule::requiredIf(function () {
                    return request('has_gender_and_sexuality_constituencies')
                        && count(request('gender_and_sexuality_constituencies') ?? []) === 0;
                }),
                'exclude_if:has_gender_and_sexuality_constituencies,false',
            ],
            'has_age_bracket_constituencies' => 'required|boolean',
            'age_bracket_constituencies' => 'nullable|array|required_if:has_age_bracket_constituencies,true|exclude_if:has_age_bracket_constituencies,false',
            'age_bracket_constituencies.*' => 'exists:identities,id',
            'has_ethnoracial_identity_constituencies' => 'required|boolean',
            'ethnoracial_identity_constituencies' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('has_ethnoracial_identity_constituencies') == 1
                        && ! request('has_other_ethnoracial_identity_constituency');
                }),
                'exclude_if' => 'exclude_if:has_ethnoracial_identity_constituencies,false',
            ],
            'ethnoracial_identity_constituencies.*' => 'exists:identities,id',
            'has_other_ethnoracial_identity_constituency' => 'nullable|boolean',
            'other_ethnoracial_identity_constituency' => 'nullable|array|exclude_if:has_ethnoracial_identity_constituencies,false|exclude_unless:has_other_ethnoracial_identity_constituency,true',
            'other_ethnoracial_identity_constituency.*' => 'nullable|string|max:255',
            'language_constituencies' => 'nullable|array',
            'language_constituencies.*' => [Rule::in(array_keys(get_available_languages(true)))],
            'area_type_constituencies' => 'required|array|min:1',
            'area_type_constituencies.*' => 'exists:identities,id',
            'staff_lived_experience' => 'required|string|in:yes,no,prefer-not-to-answer',
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'lived_experience_constituencies' => [],
            'base_disability_type' => null,
            'disability_and_deaf_constituencies' => [],
            'area_type_constituencies' => [],
            'nb_gnc_fluid_identity' => 0,
            'gender_and_sexuality_constituencies' => [],
            'indigenous_constituencies' => [],
            'age_bracket_constituencies' => [],
            'ethnoracial_identity_constituencies' => [],
            'has_other_ethnoracial_identity_constituency' => 0,
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function withValidator($validator)
    {
        $validator->sometimes('other_disability_constituency.en', 'required_without:other_disability_constituency.fr', function ($input) {
            return $input->has_other_disability_constituency;
        });

        $validator->sometimes('other_disability_constituency.fr', 'required_without:other_disability_constituency.en', function ($input) {
            return $input->has_other_disability_constituency;
        });

        $validator->sometimes('other_ethnoracial_identity_constituency.en', 'required_without:other_ethnoracial_identity_constituency.fr', function ($input) {
            return $input->has_other_ethnoracial_identity_constituency;
        });

        $validator->sometimes('other_ethnoracial_identity_constituency.fr', 'required_without:other_ethnoracial_identity_constituency.en', function ($input) {
            return $input->has_other_ethnoracial_identity_constituency;
        });
    }

    public function messages(): array
    {
        return [
            'disability_and_deaf.required_without' => __('You must select at least one option for "Do you specifically :represent_or_serve_and_support people with disabilities, Deaf persons, and/or their supporters?"', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'lived_experience_constituencies.required_if' => __('You must select at least one option for "Do you specifically :represent_or_serve_and_support people with disabilities, Deaf persons, and/or their supporters?"', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'base_disability_type.required_if' => __('You must select one option for “Please select people with disabilities that you specifically :represent_or_serve_and_support”.', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'disability_and_deaf_constituencies.required' => __('You must select which specific disability and/or Deaf groups your organization :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'area_type_constituencies.required' => __('You must select at least one option for “Where do the people that you :represent_or_serve_and_support come from?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'has_indigenous_constituencies.required' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support people who are First Nations, Inuit, or Métis?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'indigenous_constituencies.required_if' => __('You must select at least one Indigenous group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'refugees_and_immigrants' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support refugees and/or immigrants?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'has_gender_and_sexuality_constituencies.required' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support people who are marginalized based on gender or sexual identity?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'gender_and_sexuality_constituencies.required' => __('You must select at least one gender or sexual identity group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'nb_gnc_fluid_identity.required' => __('You must select at least one gender or sexual identity group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'has_age_bracket_constituencies.required' => __('You must select one option for “Does your organization :represent_or_serve_and_support a specific age bracket or brackets?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'age_bracket_constituencies.required_if' => __('You must select at least one age group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'has_ethnoracial_identity_constituencies.required' => __('You must select one option for “Does your organization :represent_or_serve_and_support a specific ethnoracial identity or identities?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'ethnoracial_identity_constituencies.required' => __('You must select at least one ethno-racial identity your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $this->organization->type === 'representative' ? __('represents') : __('serves and supports')]),
            'language_constituencies.*.in' => __('You must select a language.'),
            'staff_lived_experience.required' => __('You must select one option for “Do you have staff who have lived experience of the people you :represent_or_serve_and_support?”', ['represent_or_serve_and_support' => $this->organization->type === 'representative' ? __('represent') : __('serve and support')]),
            'other_disability_constituency.*.required_without' => __('There is no disability type filled in under "something else". Please fill this in.'),
            'other_ethnoracial_identity_constituency.*.required_without' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
        ];
    }
}
