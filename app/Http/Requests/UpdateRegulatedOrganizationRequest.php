<?php

namespace App\Http\Requests;

use App\Enums\ProvinceOrTerritory;
use App\Models\Sector;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
                new Enum(ProvinceOrTerritory::class),
            ],
            'service_areas' => [
                'required',
                'array',
            ],
            'service_areas.*' => [
                new Enum(ProvinceOrTerritory::class),
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
            'accessibility_and_inclusion_links.*.url' => 'nullable|active_url|required_with:accessibility_and_inclusion_links.*.title',
            'social_links.*' => 'nullable|active_url',
            'website_link' => 'nullable|active_url',
            'contact_person_name' => 'required|string',
            'contact_person_email' => 'nullable|email|required_without:contact_person_phone',
            'contact_person_phone' => 'nullable|phone:CA|required_if:contact_person_vrs,true|required_without:contact_person_email',
            'contact_person_vrs' => 'nullable|boolean',
            'preferred_contact_method' => 'required|in:email,phone',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'accessibility_and_inclusion_links' => array_map(function ($item) {
                $item['url'] = normalize_url($item['url']);

                return $item;
            }, $this->accessibility_and_inclusion_links ?? []),
            'social_links' => array_map('normalize_url', $this->social_links ?? []),
            'website_link' => normalize_url($this->website_link),
        ]);
    }

    public function attributes(): array
    {
        return [
            'locality' => __('city or town'),
            'region' => __('province or territory'),
            'contact_person_email' => __('email address'),
            'contact_person_phone' => __('phone number'),
            'about.fr' => __('"About your organization" (French)'),
            'about.en' => __('"About your organization" (English)'),
        ];
    }
}
