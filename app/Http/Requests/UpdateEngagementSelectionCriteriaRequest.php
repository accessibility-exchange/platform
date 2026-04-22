<?php

namespace App\Http\Requests;

use App\Enums\IdentityType;
use App\Enums\LocationType;
use App\Enums\ProvinceOrTerritory;
use App\Enums\WhoToEngage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateEngagementSelectionCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_type' => ['required', new Enum(LocationType::class)],
            'regions' => 'nullable|array|required_if:location_type,'.LocationType::Regions->value.'|exclude_if:location_type,'.LocationType::Localities->value,
            'regions.*' => [new Enum(ProvinceOrTerritory::class)],
            'locations' => 'nullable|array|required_if:location_type,'.LocationType::Localities->value.'|exclude_if:location_type,'.LocationType::Regions->value,
            'locations.*.region' => ['required', new Enum(ProvinceOrTerritory::class)],
            'locations.*.locality' => 'required|string',
            'cross_disability_and_deaf' => 'required|boolean',
            'disability_types' => 'nullable|array|required_if:cross_disability_and_deaf,false|exclude_if:cross_disability_and_deaf,true',
            'disability_types.*' => 'exists:identities,id',
            'intersectional' => 'required|boolean',
            'other_identity_type' => [
                'nullable',
                new Enum(IdentityType::class),
                'required_if:intersectional,false',
                'exclude_if:intersectional,true',
            ],
            'age_brackets' => 'nullable|array|required_if:other_identity_type,'.IdentityType::AgeBracket->value.'|exclude_unless:other_identity_type,'.IdentityType::AgeBracket->value,
            'age_brackets.*' => 'exists:identities,id',
            'gender_and_sexual_identities' => 'nullable|array|required_if:other_identity_type,'.IdentityType::GenderAndSexualIdentity->value.'|exclude_unless:other_identity_type,'.IdentityType::GenderAndSexualIdentity->value,
            'gender_and_sexual_identities.*' => 'exists:identities,id',
            'nb_gnc_fluid_identity' => [
                'nullable',
                'boolean',
                Rule::requiredIf(function () {
                    return request('other_identity_type') === IdentityType::GenderAndSexualIdentity->value
                        && (! is_array(request('gender_and_sexual_identities')) || count(request('gender_and_sexual_identities')) === 0);
                }),
                'exclude_unless:other_identity_type,'.IdentityType::GenderAndSexualIdentity->value,
            ],
            'indigenous_identities' => 'nullable|array|required_if:other_identity_type,'.IdentityType::IndigenousIdentity->value.'|exclude_unless:other_identity_type,'.IdentityType::IndigenousIdentity->value,
            'indigenous_identities.*' => 'exists:identities,id',
            'ethnoracial_identities' => 'nullable|array|required_if:other_identity_type,'.IdentityType::EthnoracialIdentity->value.'|exclude_unless:other_identity_type,'.IdentityType::EthnoracialIdentity->value,
            'ethnoracial_identities.*' => 'exists:identities,id',
            'first_languages' => 'nullable|array|required_if:other_identity_type,'.IdentityType::FirstLanguage->value.'|exclude_unless:other_identity_type,'.IdentityType::FirstLanguage->value,
            'first_languages.*' => [Rule::in(array_keys(get_available_languages(true)))],
            'area_types' => 'nullable|array|required_if:other_identity_type,'.IdentityType::AreaType->value.'|exclude_unless:other_identity_type,'.IdentityType::AreaType->value,
            'area_types.*' => 'exists:identities,id',
            'ideal_participants' => [
                'nullable',
                Rule::requiredIf(function () {
                    return $this->engagement->who === WhoToEngage::Individuals->value;
                }),
                'integer',
                'min:'.config('engagement.ideal_participants_floor'),
            ],
            'minimum_participants' => [
                'nullable',
                Rule::requiredIf(function () {
                    return $this->engagement->who === WhoToEngage::Individuals->value;
                }),
                'integer',
                'min:'.config('engagement.minimum_participants_floor'),
                'lte:ideal_participants',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'location_type' => __('location type'),
            'regions' => __('province or territory'),
            'regions.*' => __('province or territory'),
            'locations' => __('Location'),
            'locations.*.region' => __('location province or territory'),
            'locations.*.locality' => __('location city or town'),
            'cross_disability_and_deaf' => __('Cross disability (includes people with disabilities, Deaf people, and supporters)'),
            'intersectional' => __('intersectional'),
            'other_identity_type' => __('other identity type'),
            'age_brackets' => __('age group'),
            'age_brackets.*' => __('age group'),
            'area_types' => __('area type'),
            'area_types.*' => __('area type'),
            'disability_types' => __('Disability or Deaf group'),
            'disability_types.*' => __('Disability or Deaf group'),
            'ethnoracial_identities' => __('ethnoracial group'),
            'ethnoracial_identities.*' => __('ethnoracial group'),
            'first_languages' => __('first language'),
            'first_languages.*' => __('first language'),
            'gender_and_sexual_identities' => __('gender or sexual identity group'),
            'gender_and_sexual_identities.*' => __('gender or sexual identity group'),
            'ideal_participants' => __('ideal number of participants'),
            'indigenous_identities' => __('Indigenous group'),
            'indigenous_identities.*' => __('Indigenous group'),
            'minimum_participants' => __('minimum number of participants'),
            'nb_gnc_fluid_identity' => __('Non-binary/Gender non-conforming/Gender fluid identity'),
        ];
    }

    public function messages(): array
    {
        return [
            'minimum_participants.required' => __('You must enter a :attribute.'),
            'minimum_participants.lte' => __('Please enter a :attribute that is less than or the same as the ideal number of participants.'),
            'minimum_participants.integer' => __('The :attribute must be a number.'),
            'locations.*.region' => __('You must enter a province or territory.'),
            'locations.*.locality' => __('You must enter a city or town.'),
            'locations.required_if' => __('You must enter at least one city or town.'),
            'regions.required_if' => __('You must choose at least one province or territory.'),
            'regions.*.Illuminate\Validation\Rules\Enum' => __('You must choose a valid province or territory'),
            'disability_types.required_if' => __('If you are looking for a specific :attribute, you must select at least one.'),
            'disability_types.*.exists' => __('You must select a valid :attribute.'),
            'age_brackets.required_if' => __('If you are interested in engaging a specific :attribute, you must select at least one.'),
            'age_brackets.*.exists' => __('You must select a valid :attribute.'),
            'gender_and_sexual_identities.required_if' => __('If you are interested in engaging a specific :attribute, you must select at least one.'),
            'gender_and_sexual_identities.required' => __('If you are interested in engaging a specific :attribute, you must select at least one.'),
            'gender_and_sexual_identities.*.exists' => __('You must select a valid :attribute.'),
            'nb_gnc_fluid_identity.required' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('gender or sexual identity group')]),
            'indigenous_identities.required_if' => __('If you are interested in engaging a specific :attribute, you must select at least one.'),
            'indigenous_identities.*.exists' => __('You must select a valid :attribute.'),
            'ethnoracial_identities.required_if' => __('If you are interested in engaging a specific :attribute, you must select at least one.'),
            'ethnoracial_identities.*.exists' => __('You must select a valid :attribute.'),
            'first_languages.required_if' => __('If you are interested in engaging a specific :attribute, you must select at least one.'),
            'first_languages.*.in' => __('You must select a valid :attribute.'),
            'area_types.required_if' => __('If you are interested in engaging a specific :attribute, you must select at least one.'),
            'area_types.*.exists' => __('You must select a valid :attribute.'),
            'other_identity_type.required_if' => __('If you are looking for a group with a specific experience or identity, you must select which type of experience or identity you are looking for.'),
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'regions' => [],
            'locations' => [],
            'disability_types' => [],
            'age_brackets' => [],
            'gender_and_sexual_identities' => [],
            'indigenous_identities' => [],
            'ethnoracial_identities' => [],
            'area_types' => [],
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }
}
