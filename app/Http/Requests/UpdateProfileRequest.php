<?php

namespace App\Http\Requests;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $profile = $this->route('profile');

        return $profile && $this->user()->can('update', $profile);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $profile = $this->route('profile');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Profile::class)->ignore($profile->id),

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
            'visibility' => 'required|in:project,all',
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
        ];
    }
}
