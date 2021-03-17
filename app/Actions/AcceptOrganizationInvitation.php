<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AcceptOrganizationInvitation
{
    /**
     * Add a new organization member to the given organization.
     *
     * @param  mixed  $organization
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    public function accept($organization, string $email, string $role = null)
    {
        $this->validate($organization, $email, $role);

        $newMember = User::where('email', $email)->first();

        $organization->users()->attach(
            $newMember,
            ['role' => $role]
        );
    }

    /**
     * Validate the add member operation.
     *
     * @param  mixed  $organization
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    protected function validate($organization, string $email, ?string $role)
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules(), [
            'email.exists' => __('organization.user_with_email_not_found'),
        ])->after(
            $this->ensureUserIsNotAlreadyInOrganization($organization, $email)
        )->validateWithBag('acceptOrganizationInvitation');
    }

    /**
     * Get the validation rules for adding a organization member.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'email' => ['required', 'email', 'exists:users'],
            'role' => ['required', 'string', Rule::in(config('roles'))]
        ];
    }

    /**
     * Ensure that the user is not already on the organization.
     *
     * @param  mixed  $organization
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyInOrganization($organization, string $email)
    {
        return function ($validator) use ($organization, $email) {
            $validator->errors()->addIf(
                $organization->hasUserWithEmail($email),
                'email',
                __('organization.invited_user_already_in_organization')
            );
        };
    }
}
