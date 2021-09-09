<?php

namespace App\Http\Requests;

use App\Models\Consultant;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateConsultantRequest extends FormRequest
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
                Rule::unique(Consultant::class),
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
            'creator_name' => 'required_if:creator,other|nullable|string|max:255',
            'creator_relationship' => 'required_if:creator,other|nullable|string|max:255',
            'user_id' => [
                Rule::unique(Consultant::class),
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
            'name.unique' => 'A consultant page with this name already exists.',
            'user_id.unique' => 'You already have a consultant page. Would you like to edit it instead?',
            'links.*.url.url' => 'The link must be a valid web address.',
            'links.*.url.required_unless' => 'The link address must be filled in if the link text is filled in.',
            'links.*.text.required_unless' => 'The link text must be filled in if the link address is filled in.',
        ];
    }
}
