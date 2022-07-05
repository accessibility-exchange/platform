<?php

namespace App\Http\Requests;

use App\Models\User;
use Hearth\Models\Membership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class AcceptInvitationRequest extends FormRequest
{
    protected $errorBag = 'acceptInvitation';

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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator
            ->after($this->ensureInviteeHasNoExistingMemberships(Auth::user()))
            ->after($this->ensureInviteeIsNotAlreadyAMember($this->invitation->invitationable, $this->invitation->email))
            ->after($this->ensureCurrentUserIsInvitee(Auth::user(), $this->invitation->email));
    }

    /**
     * Ensure that the user is not already part of a team.
     *
     * @param  User  $user
     * @return \Closure
     */
    protected function ensureInviteeHasNoExistingMemberships(User $user): \Closure
    {
        return function ($validator) use ($user) {
            $validator->errors()->addIf(
                Membership::where('user_id', $user->id)->first(),
                'email',
                __('invitation.invited_user_already_belongs_to_a_team')
            );
        };
    }

    /**
     * Ensure that the user is not already part of this team.
     *
     * @param  mixed  $invitationable
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureInviteeIsNotAlreadyAMember(mixed $invitationable, string $email): \Closure
    {
        return function ($validator) use ($invitationable, $email) {
            $validator->errors()->addIf(
                $invitationable->hasUserWithEmail($email),
                'email',
                __('invitation.invited_user_already_belongs_to_this_team')
            );
        };
    }

    /**
     * Ensure that the authenticated user is the user who was invited.
     *
     * @param  User  $user
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureCurrentUserIsInvitee(User $user, string $email): \Closure
    {
        return function ($validator) use ($user, $email) {
            if ($user->email !== $email) {
                $validator->errors()->add(
                    'email',
                    __('invitation.email_not_valid', ['email' => $user->email])
                );
            }
        };
    }
}
