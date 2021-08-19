<?php

namespace App\Http\Requests;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProfileRequest extends FormRequest
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
                Rule::unique(Profile::class),

            ],
            'bio' => 'required|string',
            'locality' => 'required|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'birth_date' => 'nullable|date',
            'pronouns' => 'nullable|string',
            'creator' => 'required|in:self,other',
            'creator_name' => 'required_if:creator,other|nullable|string|max:255',
            'creator_relationship' => 'required_if:creator,other|nullable|string|max:255',
            'visibility' => 'required|in:team,all',
            'user_id' => [
                Rule::unique(Profile::class),
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
        ];
    }
}
