<?php

namespace App\Http\Requests;

use App\Models\DisabilityType;
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
                'in:cross_disability,specific_disabilities',
                Rule::requiredIf(function () {
                    return in_array(1, request('lived_experiences') ?? []);
                }),
            ],
            'disability_types' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return in_array(1, request('lived_experiences') ?? []) && request('base_disability_type') === 'specific_disability';
                }),
                'exclude_if:base_disability_type,cross_disability',
            ],
            'disability_types.*' => 'exists:disability_types,id',
            'other_disability_type.*' => 'nullable|string|max:255',
            'indigenous_identities' => 'nullable|array|required_if:base_indigenous_identity,1|exclude_if:base_indigenous_identity,0',
            'indigenous_identities.*' => 'exists:indigenous_identities,id',
            'refugees_and_immigrants' => 'sometimes|boolean',
            'gender_identities' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return request('base_gender_and_sexual_identity') == 1 && request('trans_people') == 0 && request('twoslgbtqia') == 0;
                }),
                'exclude_if:base_gender_and_sexual_identity,0',
            ],
            'gender_identities.*' => 'exists:gender_identities,id',
            'trans_people' => [
                'nullable',
                'boolean',
                Rule::requiredIf(function () {
                    return request('base_gender_and_sexual_identity') == 1 && empty(request('gender_identities')) && request('twoslgbtqia') == 0;
                }),
                'exclude_if:base_gender_and_sexual_identity,0',
            ],
            'twoslgbtqia' => [
                'nullable',
                'boolean',
                Rule::requiredIf(function () {
                    return request('base_gender_and_sexual_identity') == 1 && empty(request('gender_identities')) && request('trans_people') == 0;
                }),
                'exclude_if:base_gender_and_sexual_identity,0',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $otherDisability = DisabilityType::where('name->en', '=', 'Other')->first();

        $validator->sometimes('other_disability_type.en', 'required_without:other_disability_type.fr', function ($input) use ($otherDisability) {
            return in_array($otherDisability->id, $input->disability_types ?? []);
        });

        $validator->sometimes('other_disability_type.fr', 'required_without:other_disability_type.en', function ($input) use ($otherDisability) {
            return in_array($otherDisability->id, $input->disability_types ?? []);
        });
    }
}
