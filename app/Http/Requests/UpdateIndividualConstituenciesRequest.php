<?php

namespace App\Http\Requests;

use App\Enums\BaseDisabilityType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
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
            'disability_and_deaf' => 'nullable|boolean|required_without:lived_experiences',
            'lived_experiences' => 'nullable|array|required_if:disability_and_deaf,false',
            'lived_experiences.*' => 'exists:identities,id',
            'base_disability_type' => [
                'nullable',
                'required_if:disability_and_deaf,true',
                'exclude_if:disability_and_deaf,false',
                'exclude_without:disability_and_deaf',
                new Enum(BaseDisabilityType::class),
            ],
            'disability_and_deaf_connections' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('base_disability_type') === 'specific_disabilities'
                        && ! request('has_other_disability_connection');
                }),
                'exclude_if:disability_and_deaf,false',
                'exclude_without:disability_and_deaf',
                'exclude_if:base_disability_type,cross_disability_and_deaf',
                'exclude_without:base_disability_type',
            ],
            'disability_and_deaf_connections.*' => 'exists:identities,id',
            'has_other_disability_connection' => 'nullable|boolean',
            'other_disability_connection' => 'nullable|array|exclude_if:base_disability_type,cross_disability_and_deaf|exclude_unless:other_disability,true',
            'other_disability_connection.*' => 'nullable|string|max:255',
            'area_types' => 'required|array|min:1',
            'area_types.*' => 'exists:identities,id',
            'has_indigenous_identity_connections' => 'required|boolean',
            'indigenous_identity_connections' => 'nullable|array|required_if:has_indigenous_identity_connections,true|exclude_if:has_indigenous_identity_connections,false',
            'indigenous_identity_connections.*' => 'exists:identities,id',
            'refugees_and_immigrants' => 'required|boolean',
            'has_gender_and_sexuality_connections' => 'required|boolean',
            'gender_and_sexuality_connections' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('has_gender_and_sexuality_connections')
                        && ! request('nb_gnc_fluid_identity');
                }),
                'exclude_if:has_gender_and_sexuality_connections,false',
            ],
            'gender_and_sexuality_connections.*' => 'exists:identities,id',
            'nb_gnc_fluid_identity' => [
                'nullable',
                'boolean',
                Rule::requiredIf(function () {
                    return request('has_gender_and_sexuality_connections')
                        && count(request('gender_and_sexuality_connections') ?? []) === 0;
                }),
                'exclude_if:has_gender_and_sexuality_connections,false',
            ],
            'has_age_bracket_connections' => 'required|boolean',
            'age_bracket_connections' => 'nullable|array|required_if:has_age_bracket_connections,true|exclude_if:has_age_bracket_connections,false',
            'age_bracket_connections.*' => 'exists:identities,id',
            'has_ethnoracial_identity_connections' => 'required|boolean',
            'ethnoracial_identity_connections' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('has_ethnoracial_identity_connections') == 1
                        && ! request('has_other_ethnoracial_identity_connection');
                }),
            ],
            'ethnoracial_identity_connections.*' => 'exists:identities,id',
            'has_other_ethnoracial_identity_connection' => 'nullable|boolean',
            'other_ethnoracial_identity_connection' => 'nullable|array|exclude_if:has_ethnoracial_identity_connections,false|exclude_unless:has_other_ethnoracial_identity_connection,true',
            'other_ethnoracial_identity_connection.*' => 'nullable|string|max:255',
            'language_connections' => 'nullable|array',
            'language_connections.*' => [Rule::in(array_keys(get_available_languages(true)))],
            'connection_lived_experience' => 'required|string|in:yes-some,yes-all,no,prefer-not-to-answer',
        ];
    }

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
            'disability_and_deaf' => 0,
            'lived_experiences' => [],
            'base_disability_type' => null,
            'disability_and_deaf_connections' => [],
            'area_types' => [],
            'nb_gnc_fluid_identity' => 0,
            'indigenous_identity_connections' => [],
            'gender_and_sexuality_connections' => [],
            'age_bracket_connections' => [],
            'ethnoracial_identity_connections' => [],
        ]);
    }

    public function withValidator($validator)
    {
        $validator->sometimes('other_disability_connection.en', 'required_without:other_disability_connection.fr', function ($input) {
            return $input->has_other_disability_connection;
        });

        $validator->sometimes('other_disability_connection.fr', 'required_without:other_disability_connection.en', function ($input) {
            return $input->has_other_disability_connection;
        });

        $validator->sometimes('other_ethnoracial_identity_connection.en', 'required_without:other_ethnoracial_identity_connection.fr', function ($input) {
            return $input->has_other_ethnoracial_identity_connection;
        });

        $validator->sometimes('other_ethnoracial_identity_connection.fr', 'required_without:other_ethnoracial_identity_connection.en', function ($input) {
            return $input->has_other_ethnoracial_identity_connection;
        });
    }

    public function messages(): array
    {
        return [
            'disability_and_deaf.required_without' => __('You must select at least one option for “Can you connect to people with disabilities, Deaf persons, and/or their supporters?”'),
            'lived_experiences.required_if' => __('You must select at least one option for “Can you connect to people with disabilities, Deaf persons, and/or their supporters?”'),
            'base_disability_type.required_if' => __('You must select one option for “Please select people with disabilities that you can connect to”.'),
            'disability_and_deaf_connections.required' => __('You must select which people with specific disabilities and/or Deaf people you can connect to.'),
            'area_types.required' => __('You must select at least one option for “Where do the people that you can connect to come from?”'),
            'has_indigenous_identity_connections.required' => __('You must select one option for “Can you connect to people who are First Nations, Inuit, or Métis?”'),
            'indigenous_identity_connections.required_if' => __('You must select at least one Indigenous group you can connect to.'),
            'refugees_and_immigrants' => __('You must select one option for “Can you connect to refugees and/or immigrants?”'),
            'has_gender_and_sexuality_connections.required' => __('You must select one option for “Can you connect to people who are marginalized based on gender or sexual identity?”'),
            'gender_and_sexuality_connections.required' => __('You must select at least one gender or sexual identity group you can connect to.'),
            'nb_gnc_fluid_identity.required' => __('You must select at least one gender or sexual identity group you can connect to.'),
            'has_age_bracket_connections.required' => __('You must select one option for “Can you connect to a specific age group or groups?”'),
            'age_bracket_connections.required' => __('You must select at least one age group you can connect to.'),
            'has_ethnoracial_identity_connections.required' => __('You must select one option for “Can you connect to people with a specific ethno-racial identity or identities?”'),
            'ethnoracial_identity_connections.required_if' => __('You must select at least one ethno-racial identity you can connect to.'),
            'language_connections.*.in' => __('You must select a language.'),
            'connection_lived_experience.required' => __('You must select one option for “Do you have lived experience of the people you can connect to?”'),
            'other_disability_connection.*.required_without' => __('There is no disability type filled in under "something else". Please fill this in.'),
            'other_ethnoracial_identity_connection.*.required_without' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
        ];
    }
}
