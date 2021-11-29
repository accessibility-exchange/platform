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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(CommunityMember::class)->ignore($communityMember->id),

            ],
            'picture' => 'nullable|file|image|dimensions:min_width=200,min_height=200',
            'picture_alt' => 'nullable|string|required_unless:picture,null',
            'bio' => 'required|string',
            'links.*.url' => 'nullable|url|required_unless:links.*.text,null',
            'links.*.text' => 'nullable|string|required_unless:links.*.url,null',
            'locality' => 'required|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'pronouns' => 'nullable|string',
            'creator' => 'required|in:self,other',
            'creator_name' => 'required_if:creator,other|nullable|string|max:255',
            'creator_relationship' => 'required_if:creator,other|nullable|string|max:255',
            'user_id' => [
                Rule::unique(CommunityMember::class),
            ],
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
            'picture_alt.required_unless' => __('You must provide alternative text for your picture.'),
            'name.unique' => __('A community member page with this name already exists.'),
            'links.*.url.url' => __('The link must be a valid web address.'),
            'links.*.url.required_unless' => __('The link address must be filled in if the link text is filled in.'),
            'links.*.text.required_unless' => __('The link text must be filled in if the link address is filled in.'),
        ];
    }
}
