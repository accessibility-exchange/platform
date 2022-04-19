<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class DestroyUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
                    __('hearth::auth.wrong_password')
                );
            }
            if (count($this->user()->organizations) > 0) {
                foreach ($this->user()->organizations as $organization) {
                    if ($organization->administrators()->count() === 1 && $this->user()->isAdministratorOf($organization)) {
                        $validator->errors()->add(
                            'organizations',
                            __(
                                'You must assign a new administrator to :organization before deleting your account.',
                                ['organization' => '<a href="' . localized_route('organizations.edit', $organization) . '">' . $organization->name . '</a>'],
                            )
                        );
                    }
                }
            }

            if (count($this->user()->regulatedOrganizations) > 0) {
                foreach ($this->user()->regulatedOrganizations as $regulatedOrganization) {
                    if ($regulatedOrganization->administrators()->count() === 1 && $this->user()->isAdministratorOf($regulatedOrganization)) {
                        $validator->errors()->add(
                            'regulatedOrganizations',
                            __(
                                'You must assign a new administrator to :regulatedOrganization before deleting your account.',
                                ['regulatedOrganization' => '<a href="' . localized_route('regulated-organizations.edit', $regulatedOrganization) . '">' . $regulatedOrganization->name . '</a>'],
                            )
                        );
                    }
                }
            }
        })->validateWithBag('destroyAccount');
    }
}
