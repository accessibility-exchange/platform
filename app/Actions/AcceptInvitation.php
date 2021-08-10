<?php

namespace App\Actions;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AcceptInvitation
{
    /**
     * Add a new organization member to the given organization.
     *
     * @param  mixed  $inviteable
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    public function accept($inviteable, string $email, string $role = null)
    {
        $this->validate($inviteable, $email, $role);

        $newMember = User::where('email', $email)->first();

        $inviteable->users()->attach(
            $newMember,
            ['role' => $role]
        );
    }

    /**
     * Validate the add member operation.
     *
     * @param  mixed  $inviteable
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    protected function validate($inviteable, string $email, ?string $role)
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules(), [
            'email.exists' => __('invitation.user_with_email_not_found'),
        ])->after(
            $this->ensureUserIsNotAlreadyAMember($inviteable, $email)
        )->validateWithBag('acceptInvitation');
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
            'role' => ['required', 'string', Rule::in(config('hearth.organizations.roles'))],
        ];
    }

    /**
     * Ensure that the user is not already on the organization.
     *
     * @param  mixed  $inviteable
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyAMember($inviteable, string $email)
    {
        return function ($validator) use ($inviteable, $email) {
            $validator->errors()->addIf(
                $inviteable->hasUserWithEmail($email),
                'email',
                __('invitation.invited_user_already_belongs_to_team')
            );
        };
    }
}
