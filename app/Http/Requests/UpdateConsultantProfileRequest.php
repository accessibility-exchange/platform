<?php

namespace App\Http\Requests;

use App\Models\ConsultantProfile;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultantProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $consultantProfile = $this->route('consultantProfile');

        return $consultantProfile && $this->user()->can('update', $consultantProfile);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $consultantProfile = $this->route()->parameter('consultantProfile');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(ConsultantProfile::class)->ignore($this->route()->parameter('consultantProfile')->id),

            ],
            'locality' => ['required', 'string', 'max:255'],
            'region' => [
                'required',
                Rule::in(config('regions'))
            ]
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
            'name.unique' => 'A consultant profile with this name already exists.'
        ];
    }
}
