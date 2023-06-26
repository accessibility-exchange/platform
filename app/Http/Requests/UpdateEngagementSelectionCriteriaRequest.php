<?php

namespace App\Http\Requests;

use App\Enums\LocationType;
use App\Enums\ProvinceOrTerritory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateEngagementSelectionCriteriaRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_type' => ['required', new Enum(LocationType::class)],
            'regions' => 'nullable|array|required_if:location_type,regions|exclude_if:location_type,localities',
            'regions.*' => [new Enum(ProvinceOrTerritory::class)],
            'locations' => 'nullable|array|required_if:location_type,localities|exclude_if:location_type,regions',
            'locations.*.region' => ['required', new Enum(ProvinceOrTerritory::class)],
            'locations.*.locality' => 'required|string',
            'cross_disability_and_deaf' => 'required|boolean',
            'disability_types' => 'nullable|array|required_if:cross_disability_and_deaf,false|exclude_if:cross_disability_and_deaf,true',
            'disability_types.*' => 'exists:identities,id',
            'intersectional' => 'required|boolean',
            'other_identity_type' => 'nullable|string|required_if:intersectional,false|exclude_if:intersectional,true',
            'age_brackets' => 'nullable|array|required_if:other_identity_type,age-bracket|exclude_unless:other_identity_type,age-bracket',
            'age_brackets.*' => 'exists:identities,id',
            'gender_and_sexual_identities' => 'nullable|array|required_if:other_identity_type,gender-and-sexual-identity|exclude_unless:other_identity_type,gender-and-sexual-identity',
            'gender_and_sexual_identities.*' => 'exists:identities,id',
            'nb_gnc_fluid_identity' => [
                'nullable',
                'boolean',
                Rule::requiredIf(function () {
                    return request('other_identity_type') === 'gender-and-sexual-identity'
                        && (! is_array(request('gender_and_sexual_identities')) || count(request('gender_and_sexual_identities')) === 0);
                }),
                'exclude_unless:other_identity_type,gender-and-sexual-identity',
            ],
            'indigenous_identities' => 'nullable|array|required_if:other_identity_type,indigenous-identity|exclude_unless:other_identity_type,indigenous-identity',
            'indigenous_identities.*' => 'exists:identities,id',
            'ethnoracial_identities' => 'nullable|array|required_if:other_identity_type,ethnoracial-identity|exclude_unless:other_identity_type,ethnoracial-identity',
            'ethnoracial_identities.*' => 'exists:identities,id',
            'first_languages' => 'nullable|array|required_if:other_identity_type,first-language|exclude_unless:other_identity_type,first-language',
            'first_languages.*' => [Rule::in(array_keys(get_available_languages(true)))],
            'area_types' => 'nullable|array|required_if:other_identity_type,area-type|exclude_unless:other_identity_type,area-type',
            'area_types.*' => 'exists:identities,id',
            'ideal_participants' => [
                'nullable',
                Rule::requiredIf(function () {
                    return $this->engagement->who === 'individuals';
                }),
                'integer',
                'min:10',
            ],
            'minimum_participants' => [
                'nullable',
                Rule::requiredIf(function () {
                    return $this->engagement->who === 'individuals';
                }),
                'integer',
                'min:10',
                'lte:ideal_participants',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nb_gnc_fluid_identity' => __('Non-binary/Gender non-conforming/Gender fluid identity'),
        ];
    }

    public function messages(): array
    {
        return [
            'minimum_participants.lte' => __('The minimum number of participants is more than the ideal number of participants. Please enter a minimum that is less than or the same as the ideal number of participants.'),
            'locations.*.region' => __('You must enter a province or territory.'),
            'locations.*.locality' => __('You must enter a city or town.'),
            'locations.required_if' => __('You must enter at least one city or town.'),
            'regions.required_if' => __('You must choose at least one province or territory.'),
            'regions.*.Illuminate\Validation\Rules\Enum' => __('You must choose a valid province or territory'),
            'disability_types.required_if' => __('One or more Disability or Deaf groups are required.'),
            'disability_types.*.exists' => __('You must select a valid Disability or Deaf group.'),
            'age_brackets.*.exists' => __('You must select a valid age bracket.'),
            'gender_and_sexual_identities.required_if' => __('You must select at least one gender or sexual identity group.'),
            'gender_and_sexual_identities.required' => __('You must select at least one gender or sexual identity group.'),
            'gender_and_sexual_identities.*.exists' => __('You must select a valid gender or sexual identity.'),
            'nb_gnc_fluid_identity.required' => __('You must select at least one gender or sexual identity group.'),
            'indigenous_identities.*.exists' => __('You must select a valid indigenous identity.'),
            'ethnoracial_identities.*.exists' => __('You must select a valid ethnoracial identity.'),
            'first_languages.*.in' => __('You must select a valid first language.'),
            'area_types.*.exists' => __('You must select a valid area type.'),
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
