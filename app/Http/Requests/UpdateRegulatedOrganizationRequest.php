<?php

namespace App\Http\Requests;

use App\Enums\ProvinceOrTerritory;
use App\Models\Sector;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateRegulatedOrganizationRequest extends FormRequest
{
    use HasFactory;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->regulatedOrganization);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'name.*' => [
                'nullable',
                'string',
                'max:255',
                UniqueTranslationRule::for('regulated_organizations')->ignore($this->regulatedOrganization->id),
            ],
            'locality' => 'required|string|max:255',
            'region' => [
                'required',
                Rule::enum(ProvinceOrTerritory::class),
            ],
            'service_areas' => [
                'required',
                'array',
            ],
            'service_areas.*' => [
                Rule::enum(ProvinceOrTerritory::class),
            ],
            'sectors' => [
                'required',
                'array',
            ],
            'sectors.*' => [
                Rule::in(Sector::pluck('id')->toArray()),
            ],
            'about.*' => 'nullable|string',
            'about.en' => 'string|required_without:about.fr',
            'about.fr' => 'string|required_without:about.en',
            'accessibility_and_inclusion_links.*.title' => 'nullable|string|required_with:accessibility_and_inclusion_links.*.url',
            'accessibility_and_inclusion_links.*.url' => 'nullable|active_url|required_with:accessibility_and_inclusion_links.*.title',
            'social_links.*' => 'nullable|active_url',
            'website_link' => 'nullable|active_url',
            'contact_person_name' => 'required|string',
            'contact_person_email' => 'nullable|email|required_without:contact_person_phone|required_if:preferred_contact_method,email',
            'contact_person_phone' => 'nullable|phone:CA|required_without:contact_person_email|required_if:preferred_contact_method,phone',
            'contact_person_vrs' => 'nullable|boolean',
            'preferred_contact_method' => 'required|in:email,phone',
            'preferred_contact_language' => [
                'required',
                Rule::in(get_supported_locales(false)),
            ],
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'contact_person_vrs' => null,
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks)
            ->merge([
                'accessibility_and_inclusion_links' => array_map(function ($item) {
                    $item['url'] = normalize_url($item['url'] ?? null);

                    return $item;
                }, $this->accessibility_and_inclusion_links ?? []),
                'social_links' => array_map('normalize_url', $this->social_links ?? []),
                'website_link' => normalize_url($this->website_link),
            ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->contact_person_vrs && ! $this->contact_person_phone) {
                $validator->errors()->add(
                    'contact_person_phone',
                    __('Since you have indicated that your contact person needs VRS, please enter a phone number.')
                );
            }
        });
    }

    public function attributes(): array
    {
        return [
            'name.en' => __('organization name (English)'),
            'name.fr' => __('organization name (French)'),
            'name.*' => __('organization name'),
            'locality' => __('city or town'),
            'region' => __('province or territory'),
            'service_areas' => __('Service areas'),
            'service_areas.*' => __('Service areas'),
            'sectors' => __('type of Regulated Organization'),
            'sectors.*' => __('type of Regulated Organization'),
            'about.fr' => __('“About your organization” (French)'),
            'about.en' => __('“About your organization” (English)'),
            'about.*' => __('“About your organization”'),
            'accessibility_and_inclusion_links.*.title' => __('accessibility and inclusion link title'),
            'accessibility_and_inclusion_links.*.url' => __('accessibility and inclusion link'),
            'social_links.*' => __('Social media links'),
            'website_link' => __('Website link'),
            'contact_person_name' => __('Contact person'),
            'contact_person_email' => __('email address'),
            'contact_person_phone' => __('phone number'),
            'contact_person_vrs' => __('Contact person requires Video Relay Service (VRS) for phone calls'),
            'preferred_contact_method' => __('preferred contact method'),
            'preferred_contact_language' => __('preferred contact language'),
        ];
    }

    public function messages(): array
    {
        $messages = [
            'name.*.required_without' => __('You must enter your organization name.'),
            'accessibility_and_inclusion_links.*.title.required_with' => __('Since a website link under “Accessibility and Inclusion links” has been entered, you must also enter a website title.'),
            'accessibility_and_inclusion_links.*.url.required_with' => __('Since a website title under “Accessibility and Inclusion links” has been entered, you must also enter a website link.'),
            'accessibility_and_inclusion_links.*.url.active_url' => __('Please enter a valid website link under “Accessibility and Inclusion links”.'),
        ];

        foreach ($this->social_links as $key => $value) {
            $messages['social_links.'.$key.'.active_url'] = __('You must enter a valid website address for :key.', ['key' => Str::studly($key)]);
        }

        return $messages;
    }
}
