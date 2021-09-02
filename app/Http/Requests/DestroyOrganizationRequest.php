<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class DestroyOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $organization = $this->route('organization');

        return $organization && $this->user()->can('delete', $organization);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! Hash::check($this->current_password, $this->user()->password)) {
                $validator->errors()->add(
                    'current_password',
                    __('validation.current_password')
                );
            }
        })->validateWithBag('destroyOrganization');

        return;
    }
}
