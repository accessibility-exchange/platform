<?php

namespace App\Http\Requests;

use App\Enums\ConsultingService;
use App\Enums\ProvinceOrTerritory;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateIndividualRequest extends FormRequest
{
    use HasFactory;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->individual);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'region' => [
                'required',
                new Enum(ProvinceOrTerritory::class),
            ],
            'pronouns' => 'nullable|array:'.implode(',', to_written_languages($this->individual->languages)),
            'bio' => 'required|array:'.implode(',', to_written_languages($this->individual->languages)).'|required_array_keys:'.get_written_language_for_signed_language($this->individual->user->locale),
            'bio.en' => 'required_without:bio.fr',
            'bio.fr' => 'required_without:bio.en',
            'bio.*' => 'nullable|string',
            'working_languages' => 'nullable|array',
            'consulting_services' => [
                'nullable',
                'array',
                Rule::requiredIf(fn () => $this->individual->isConsultant()),
                Rule::excludeIf(fn () => ! $this->individual->isConsultant()),
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
        $this->merge([
            'social_links' => array_map('normalize_url', is_array($this->social_links) ? $this->social_links : []),
            'website_link' => normalize_url($this->website_link),
        ]);
    }

    public function attributes(): array
    {
        return [
            'region' => __('province or territory'),
        ];
    }

    public function messages(): array
    {
        $messages = [
            'bio.required_array_keys' => __('Your bio must be provided in at least English or French.'),
            'bio.*.required_without' => __('Your bio must be provided in at least English or French.'),
            'bio.array' => __('Your bio must be provided in at least English or French.'),
            'consulting_services.*.Illuminate\Validation\Rules\Enum' => __('The selected consulting service is invalid'),
            'pronouns.array' => __('Your pronouns must be provided in at least English or French.'),
            'website_link.active_url' => __('You must enter a valid website link.'),
        ];

        foreach ($this->social_links as $key => $value) {
            $messages['social_links.'.$key.'.active_url'] = __('You must enter a valid link for :key.', ['key' => Str::studly($key)]);
        }

        return $messages;
    }
}
