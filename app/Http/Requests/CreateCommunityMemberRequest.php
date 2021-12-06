<?php

namespace App\Http\Requests;

use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCommunityMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->id == $this->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(CommunityMember::class),
            ],
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
            'user_id.unique' => __('You already have a community member page. Would you like to edit it instead?'),
            'links.*.url.url' => __('The link must be a valid web address.'),
            'links.*.url.required_unless' => __('The link address must be filled in if the link text is filled in.'),
            'links.*.text.required_unless' => __('The link text must be filled in if the link address is filled in.'),
        ];
    }
}
