<?php

namespace App\Http\Requests;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateOrganizationRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->organization);
    }

    public function rules(): array
    {
        $nameRules = [
            'string',
            'max:255',
            UniqueTranslationRule::for('organizations')->ignore($this->organization->id),
        ];

        $aboutRules = [
            'string',
        ];

        return [
            'name.*' => ['nullable'] + $nameRules,
            'name.en' => [
                'required_without:name.fr',
            ] + $nameRules,
            'name.fr' => [
                'required_without:name.en',
            ] + $nameRules,
            'about.*' => ['nullable'] + $aboutRules,
            'about.en' => [
                'required_without:about.fr',
            ] + $aboutRules,
            'about.fr' => [
                'required_without:about.en',
            ] + $aboutRules,
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'locality' => 'required|string|max:255',
            'service_areas' => [
                'required',
                'array',
            ],
            'service_areas.*' => [
                Rule::in(get_region_codes()),
            ],
            'working_languages' => 'required|array',
            'consulting_services' => [
                'nullable',
                'array',
                Rule::requiredIf(fn () => $this->organization->isConsultant()),
                Rule::excludeIf(fn () => ! $this->organization->isConsultant()),
            ],
            'consulting_services.*' => [
                Rule::in([
                    'booking-providers',
                    'planning-consultation',
                    'running-consultation',
                    'analysis',
                    'writing-reports',
                ]),
            ],
            'social_links.*' => 'nullable|url',
            'website_link' => 'nullable|url',
        ];
    }
}
