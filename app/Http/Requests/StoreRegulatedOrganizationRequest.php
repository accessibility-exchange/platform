<?php

namespace App\Http\Requests;

use App\Enums\RegulatedOrganizationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreRegulatedOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                new Enum(RegulatedOrganizationType::class),
            ],
            'name.en' => 'required_without:name.fr|nullable|string|max:255|unique_translation:regulated_organizations',
            'name.fr' => 'required_without:name.en|nullable|string|max:255|unique_translation:regulated_organizations',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => __('organization type'),
            'name.en' => __('organization name (English)'),
            'name.fr' => __('organization name (French)'),
            'name.*' => __('organization name'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.en.unique_translation' => __('A :type with this name already exists.', ['type' => __('regulated-organization.types.'.$this->type)]),
            'name.fr.unique_translation' => __('A :type with this name already exists.', ['type' => __('regulated-organization.types.'.$this->type)]),
            'name.*.required_without' => __('You must enter your organization name in either English or French.'),
        ];
    }
}
