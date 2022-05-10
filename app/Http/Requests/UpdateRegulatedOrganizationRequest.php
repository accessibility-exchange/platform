<?php

namespace App\Http\Requests;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegulatedOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->regulatedOrganization);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name.*' => [
                'nullable',
                'string',
                'max:255',
                UniqueTranslationRule::for('regulated_organizations')->ignore($this->regulatedOrganization->id),
            ],
            'name.en' => [
                'required_without:name.fr',
                Rule::requiredIf($this->regulatedOrganization->type === 'government'),
            ],
            'name.fr' => [
                'required_without:name.en',
                Rule::requiredIf($this->regulatedOrganization->type === 'government'),
            ],
            'locality' => 'required|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
        ];
    }
}
