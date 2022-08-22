<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:representative,support,civil-society',
            'name.*' => 'nullable|string|max:255|unique_translation:organizations',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
        ];
    }

    public function messages(): array
    {
        return [
            'name.en.unique_translation' => __('A :type with this name already exists.', ['type' => __('organization.types.'.$this->type.'.name')]),
            'name.fr.unique_translation' => __('A :type with this name already exists.', ['type' => __('organization.types.'.$this->type.'.name')]),
        ];
    }
}
