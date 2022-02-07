<?php

namespace App\Http\Requests;

use App\Models\CommunityMember;
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
    public function authorize()
    {
        $communityMember = $this->route('communityMember');

        return $communityMember && $this->user()->can('update', $communityMember);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $communityMember = $this->route('communityMember');

        return [
            'user_id' => [
                Rule::unique(CommunityMember::class),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(CommunityMember::class)->ignore($communityMember->id),

            ],
            'locality' => 'nullable|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'hide_location' => 'boolean',
            'pronouns' => 'nullable|string',
            'bio' => 'nullable|string',
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
    public function messages()
    {
        return [
            'name.unique' => __('A community member page with this name already exists.'),
            'links.*.url' => __('The link must be a valid web address.'),
            'other_links.*.url.url' => __('The link must be a valid web address.'),
            'other_links.*.url.required_with' => __('Please provide a link for the website.'),
            'other_links.*.title.required_with' => __('Please provide a title for the link.'),
        ];
    }
}
