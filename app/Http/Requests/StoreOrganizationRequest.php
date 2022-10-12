<?php

namespace App\Http\Requests;

use App\Enums\OrganizationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(OrganizationType::class)],
            'name.en' => 'required_without:name.fr|nullable|string|max:255|unique_translation:organizations',
            'name.fr' => 'required_without:name.en|nullable|string|max:255|unique_translation:organizations',
        ];
    }

    public function messages(): array
    {
        return [
            'name.en.unique_translation' => __("An organization with this name already exists on our website. Please contact your colleagues to get an invitation. If this isn't your organization, please use a different name."),
            'name.fr.unique_translation' => __("An organization with this name already exists on our website. Please contact your colleagues to get an invitation. If this isn't your organization, please use a different name."),
            'name.en.required_without' => __("You must enter your organization's name in either English or French."),
            'name.fr.required_without' => __("You must enter your organization's name in either English or French."),
        ];
    }
}
