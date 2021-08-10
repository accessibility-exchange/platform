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
            'locality' => ['required', 'string', 'max:255'],
            'region' => [
                'required',
                Rule::in(config('regions')),
            ],
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
            'name.unique' => 'A consultant profile with this name already exists.',
            'user_id.unique' => 'You already have a consultant profile. Would you like to edit it instead?',
        ];
    }
}
