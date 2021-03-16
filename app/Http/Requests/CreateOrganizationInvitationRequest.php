<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrganizationInvitationRequest extends FormRequest
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
        $organization = Organization::find($this->route('organization'))->first();

        return [
            'email' => ['required', 'email', Rule::unique('organization_invitations')->where(function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })],
            'role' => ['required', 'string', Rule::in(config('roles'))]
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
        $organization = Organization::find($this->route('organization'))->first();

        $validator->after(function ($validator) use ($organization) {
            $validator->errors()->addIf(
                $organization->hasUserWithEmail($this->email),
                'email',
                __('organization.invited_user_already_in_organization')
            );
        })->validateWithBag('inviteOrganizationMember');

        return;
    }
}
