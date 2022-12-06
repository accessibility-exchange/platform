<?php

namespace App\Http\Requests;

use App\Enums\ConsultingService;
use App\Enums\ProvinceOrTerritory;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
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
        return [
            'name.*' => [
                'nullable',
                'string',
                'max:255',
                UniqueTranslationRule::for('organizations')->ignore($this->organization->id),
            ],
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'about.*' => 'nullable|string',
            'about.en' => 'required_without:about.fr',
            'about.fr' => 'required_without:about.en',
            'region' => [
                'required',
                new Enum(ProvinceOrTerritory::class),
            ],
            'locality' => 'required|string|max:255',
            'service_areas' => [
                'required',
                'array',
            ],
            'service_areas.*' => [
                new Enum(ProvinceOrTerritory::class),
            ],
            'working_languages' => 'required|array',
            'consulting_services' => [
                'nullable',
                'array',
                Rule::requiredIf(fn () => $this->organization->isConsultant()),
                Rule::excludeIf(fn () => ! $this->organization->isConsultant()),
            ],
            'consulting_services.*' => [
                new Enum(ConsultingService::class),
            ],
            'social_links.*' => 'nullable|active_url',
            'website_link' => 'nullable|active_url',
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'social_links' => array_map('normalize_url', $this->social_links ?? []),
            'website_link' => normalize_url($this->website_link),
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function attributes(): array
    {
        return [
            'about.fr' => __('"About your organization" (French)'),
            'about.en' => __('"About your organization" (English)'),
            'locality' => __('city or town'),
            'region' => __('province or territory'),
        ];
    }

    public function messages(): array
    {
        $messages = [
            'name.*.required_without' => __('You must enter your organization name.'),
            'about.*.required_without' => __('You must fill out the field “About your organization”.'),
        ];

        foreach ($this->social_links as $key => $value) {
            $messages['social_links.'.$key.'.active_url'] = __('You must enter a valid website address for :key.', ['key' => Str::studly($key)]);
        }

        return $messages;
    }
}
