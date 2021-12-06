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

            'user_id' => [
                Rule::unique(CommunityMember::class),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(CommunityMember::class),
            ],
            'creator' => 'required|in:self,other',
            'locality' => 'required|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'pronouns' => 'nullable|string',
            'bio' => 'required|string',
            'links.*' => 'nullable|url',
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
            'user_id.unique' => __('You already have a community member page. Would you like to edit it instead?'),
            'links.*.url' => __('The link must be a valid web address.'),
        ];
    }
}
