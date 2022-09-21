<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegulatedOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:government,business,public-sector',
            'name.*' => 'nullable|string|max:255|unique_translation:regulated_organizations',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
        ];
    }

    public function messages(): array
    {
        return [
            'name.en.unique_translation' => __('A :type with this name already exists.', ['type' => __('regulated-organization.types.'.$this->type)]),
            'name.fr.unique_translation' => __('A :type with this name already exists.', ['type' => __('regulated-organization.types.'.$this->type)]),
            'name.en.required_without' => __('No name has been entered. Please enter your organization`s name in English'),
            'name.fr.required_without' => __('No name has been entered. Please enter your organization`s name in French'),
        ];
    }
}
