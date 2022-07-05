<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class DestroyUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (! Hash::check($this->current_password, $this->user()->password)) {
                $validator->errors()->add(
                    'current_password',
                    __('hearth::auth.wrong_password')
                );
            }
            if (count($this->user()->organizations) > 0) {
                if ($this->user()->organization->administrators()->count() === 1 && $this->user()->isAdministratorOf($this->user()->organization)) {
                    $validator->errors()->add(
                        'organizations',
                        __(
                            'organization.error_new_administrator_required_before_user_deletion',
                            ['organization' => '<a href="'.localized_route('organizations.edit', $this->user()->organization).'">'.$this->user()->organization->getTranslation('name', locale()).'</a>'],
                        )
                    );
                }
            }
            if (count($this->user()->regulatedOrganizations) > 0) {
                if ($this->user()->regulatedOrganization->administrators()->count() === 1 && $this->user()->isAdministratorOf($this->user()->regulatedOrganization)) {
                    $validator->errors()->add(
                        'organizations',
                        __(
                            'organization.error_new_administrator_required_before_user_deletion',
                            ['organization' => '<a href="'.localized_route('organizations.edit', $this->user()->regulatedOrganization).'">'.$this->user()->regulatedOrganization->getTranslation('name', locale()).'</a>'],
                        )
                    );
                }
            }
        })->validateWithBag('destroyAccount');
    }
}
