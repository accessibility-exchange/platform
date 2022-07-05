<?php

namespace App\Http\Requests;

use App\Models\AgeBracket;
use App\Models\Constituency;
use App\Models\LivedExperience;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIndividualRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->individual);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'pronouns' => 'nullable|array:'.implode(',', $this->individual->languages),
            'bio' => 'required|array:'.implode(',', $this->individual->languages).'|required_array_keys:'.$this->individual->user->locale,
            'first_language' => 'required|string',
            'working_languages' => 'nullable|array:'.implode(',', $this->individual->languages),
            'lived_experience_connections' => [
                'nullable',
                Rule::requiredIf($this->individual->isConnector()),
                'array',
                Rule::in(array_merge(LivedExperience::pluck('id')->toArray(), ['other'])),
            ],
            'other_lived_experience_connections' => 'nullable|array:'.implode(',', $this->individual->languages),
            'constituency_connections' => [
                'nullable',
                'array',
                Rule::in(array_merge(Constituency::pluck('id')->toArray(), ['other'])),
            ],
            'other_constituency_connections' => 'nullable|array:'.implode(',', $this->individual->languages),
            'age_bracket_connections' => [
                'nullable',
                'array',
                Rule::in(AgeBracket::pluck('id')->toArray()),
            ],
            'social_links.*' => 'nullable|url',
            'web_links.*.title' => 'nullable|string|required_with:web_links.*.url',
            'web_links.*.url' => 'nullable|url|required_with:web_links.*.title',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'social_links.*.url' => __('The link must be a valid web address.'),
            'web_links.*.url.url' => __('The link must be a valid web address.'),
            'web_links.*.url.required_with' => __('Please provide a link for the website.'),
            'web_links.*.title.required_with' => __('Please provide a title for the link.'),
        ];
    }
}
