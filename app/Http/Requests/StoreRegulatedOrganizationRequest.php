<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegulatedOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:government,business,public-sector',
            'name.en' => 'nullable|required_without:name.fr|required_if:type,government|string|max:255|unique_translation:regulated_organizations',
            'name.fr' => 'nullable|required_without:name.en|required_if:type,government|string|max:255|unique_translation:regulated_organizations',
        ];
    }

    public function messages(): array
    {
        return [
            'name.en.unique_translation' => __('A :type with this name already exists.', ['type' => __('regulated-organization.types.' . $this->type)]),
            'name.fr.unique_translation' => __('A :type with this name already exists.', ['type' => __('regulated-organization.types.' . $this->type)]),
        ];
    }
}
