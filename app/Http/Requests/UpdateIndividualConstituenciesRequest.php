<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateIndividualConstituenciesRequest extends FormRequest
{
    use HasFactory;

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
            'other_disability_type_connection' => 'nullable|array|exclude_if:base_disability_type,cross_disability|exclude_unless:other_disability,true',
            'other_disability_type_connection.*' => 'nullable|string|max:255',
            'area_types' => 'required|array|min:1',
            'area_types.*' => 'exists:area_types,id',
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
            'other_ethnoracial_identity_connection' => 'nullable|array|exclude_if:has_ethnoracial_identities,false|exclude_unless:other_ethnoracial,true',
            'other_ethnoracial_identity_connection.*' => 'nullable|string|max:255',
            'constituent_languages' => 'nullable|array',
            'constituent_languages.*' => [Rule::in(array_keys(get_available_languages(true)))],
            'connection_lived_experience' => 'required|string|in:yes-some,yes-all,no,prefer-not-to-answer',
        ];
    }

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'lived_experiences' => [],
            'disability_types' => [],
            'other_disability' => 0,
            'area_types' => [],
            'indigenous_identities' => [],
            'gender_and_sexual_identities' => [],
            'age_brackets' => [],
            'ethnoracial_identities' => [],
            'other_ethnoracial' => 0,
        ]);
    }

    public function withValidator($validator)
    {
        $validator->sometimes('other_disability_type_connection.en', 'required_without:other_disability_type_connection.fr', function ($input) {
            return $input->other_disability;
        });

        $validator->sometimes('other_disability_type_connection.fr', 'required_without:other_disability_type_connection.en', function ($input) {
            return $input->other_disability;
        });

        $validator->sometimes('other_ethnoracial_identity_connection.en', 'required_without:other_ethnoracial_identity_connection.fr', function ($input) {
            return $input->other_ethnoracial;
        });

        $validator->sometimes('other_ethnoracial_identity_connection.fr', 'required_without:other_ethnoracial_identity_connection.en', function ($input) {
            return $input->other_ethnoracial;
        });
    }

    public function messages(): array
    {
        return [
            'lived_experiences.required' => __('You must select at least one option for “Can you connect to people with disabilities, Deaf persons, and/or their supporters?”'),
            'base_disability_type.required' => __('You must select one option for “Please select people with disabilities that you can connect to”.'),
            'disability_types.required' => __('You must select which specific disability or disabilities you can connect to.'),
            'area_types.required' => __('You must select at least one option for “Where do the people that you can connect to come from?”'),
            'has_indigenous_identities.required' => __('You must select one option for “Can you connect to people who are First Nations, Inuit, or Métis?”'),
            'indigenous_identities.required_if' => __('You must select at least one Indigenous group you can connect to.'),
            'refugees_and_immigrants' => __('You must select one option for “Can you connect to refugees and/or immigrants?”'),
            'has_gender_and_sexual_identities.required' => __('You must select one option for “Can you connect to people who are marginalized based on gender or sexual identity?”'),
            'gender_and_sexual_identities.required_if' => __('You must select at least one gender or sexual identity group you can connect to.'),
            'has_age_brackets.required' => __('You must select one option for “Can you connect to a specific age group or groups?”'),
            'age_brackets.required_if' => __('You must select at least one age group you can connect to.'),
            'has_ethnoracial_identities.required' => __('You must select one option for “Can you connect to people with a specific ethno-racial identity or identities?”'),
            'ethnoracial_identities.required_if' => __('You must select at least one ethno-racial identity you can connect to.'),
            'connection_lived_experience.required' => __('You must select one option for “Do you have lived experience of the people you can connect to?”'),
            'other_disability_type_connection.*.required_without' => __('There is no disability type filled in under "something else". Please fill this in.'),
            'other_ethnoracial_identity_connection.*.required_without' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
        ];
    }
}
