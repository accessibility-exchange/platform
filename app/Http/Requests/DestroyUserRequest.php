<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class DestroyUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
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
            if ($this->user()->isOnlyAdministratorOfOrganization()) {
                $validator->errors()->add(
                    'organizations',
                    __(
                        'organization.error_new_administrator_required_before_user_deletion',
                        ['organization' => '<a href="'.localized_route('organizations.edit', $this->user()->organization).'">'.$this->user()->organization->getTranslation('name', locale()).'</a>'],
                    )
                );
            }
            if ($this->user()->isOnlyAdministratorOfRegulatedOrganization()) {
                $validator->errors()->add(
                    'organizations',
                    __(
                        'organization.error_new_administrator_required_before_user_deletion',
                        ['organization' => '<a href="'.localized_route('organizations.edit', $this->user()->regulatedOrganization).'">'.$this->user()->regulatedOrganization->getTranslation('name', locale()).'</a>'],
                    )
                );
            }
        })->validateWithBag('destroyAccount');
    }
}
