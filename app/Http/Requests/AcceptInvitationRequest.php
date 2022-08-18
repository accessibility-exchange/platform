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

    public function authorize(): bool
    {
        return $this->user()->email === $this->invitation->email;
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator(Validator $validator)
    {
        $validator
            ->after($this->ensureInviteeHasNoExistingMemberships(Auth::user()))
            ->after($this->ensureInviteeIsNotAlreadyAMember($this->invitation->invitationable, $this->invitation->email));
    }

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
}
