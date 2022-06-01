<?php

namespace App\Http\Requests;

use App\Models\Sector;
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
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->regulatedOrganization);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $nameRules = [
            'string',
            'max:255',
            UniqueTranslationRule::for('regulated_organizations')->ignore($this->regulatedOrganization->id),
        ];

        $aboutRules = [
            'string',
        ];

        return [
            'name.*' => ['nullable'] + $nameRules,
            'name.en' => [
                'required_without:name.fr',
                Rule::requiredIf($this->regulatedOrganization->type === 'government'),
            ] + $nameRules,
            'name.fr' => [
                'required_without:name.en',
                Rule::requiredIf($this->regulatedOrganization->type === 'government'),
            ] + $nameRules,
            'locality' => 'required|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'sectors' => [
                'required',
                'array',
                Rule::in(Sector::pluck('id')->toArray()),
            ],
            'about.*' => ['nullable'] + $aboutRules,
            'about.en' => [
                'required_without:about.fr',
                Rule::requiredIf($this->regulatedOrganization->type === 'government'),
            ] + $aboutRules,
            'about.fr' => [
                'required_without:about.en',
                Rule::requiredIf($this->regulatedOrganization->type === 'government'),
            ] + $aboutRules,
            'accessibility_and_inclusion_links.*.title' => 'nullable|string|required_with:accessibility_and_inclusion_links.*.url',
            'accessibility_and_inclusion_links.*.url' => 'nullable|url|required_with:accessibility_and_inclusion_links.*.title',
            'social_links.*' => 'nullable|url',
            'website_link' => 'nullable|url',
        ];
    }
}
