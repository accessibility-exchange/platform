<?php

namespace App\Http\Requests;

use App\Models\AgeGroup;
use App\Models\Community;
use App\Models\LivedExperience;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommunityMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->communityMember);
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
            'pronouns' => 'nullable|array',
            'bio' => 'required|array|required_array_keys:' . $this->communityMember->user->locale,
            'first_language' => 'required|string',
            'working_languages' => 'nullable|array',
            'lived_experience_connections' => [
                'nullable',
                Rule::requiredIf($this->communityMember->isConnector()),
                'array',
                Rule::in(array_merge(LivedExperience::pluck('id')->toArray(), ['other'])),
            ],
            'other_lived_experience_connections' => 'nullable|array',
            'community_connections' => [
                'nullable',
                'array',
                Rule::in(array_merge(Community::pluck('id')->toArray(), ['other'])),
            ],
            'other_community_connections' => 'nullable|array',
            'age_group_connections' => [
                'nullable',
                'array',
                Rule::in(AgeGroup::pluck('id')->toArray()),
            ],
            'links.*' => 'nullable|url',
            'other_links.*.title' => 'nullable|string|required_with:other_links.*.url',
            'other_links.*.url' => 'nullable|url|required_with:other_links.*.title',
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
            'links.*.url' => __('The link must be a valid web address.'),
            'other_links.*.url.url' => __('The link must be a valid web address.'),
            'other_links.*.url.required_with' => __('Please provide a link for the website.'),
            'other_links.*.title.required_with' => __('Please provide a title for the link.'),
        ];
    }
}
