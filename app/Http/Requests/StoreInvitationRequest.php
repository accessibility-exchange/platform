<?php

namespace App\Http\Requests;

use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Traits\RetrievesUserByNormalizedEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class StoreInvitationRequest extends FormRequest
{
    use RetrievesUserByNormalizedEmail;

    public function authorize(): bool
    {
        $invitationable = $this->input('invitationable_type')::where('id', $this->input('invitationable_id'))->first();

        return $invitationable && $this->user()->can('update', $invitationable);
    }

    public function rules(): array
    {
        $invitationable_type = $this->input('invitationable_type');
        $invitationable_id = $this->input('invitationable_id');
        $invitationable = $this->input('invitationable_type')::where('id', $this->input('invitationable_id'))->first();

        return [
            'email' => [
                'required',
                'email',
                Rule::unique('invitations')->where(function ($query) use ($invitationable_type, $invitationable_id) {
                    $query->where('invitationable_type', $invitationable_type)->where('invitationable_id', $invitationable_id);
                }),
                Rule::notIn($invitationable->users->pluck('email')->toArray()),
            ],
            'role' => ['required', new Enum(TeamRole::class)],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $user = $this->retrieveUserByEmail($this->input('email') ?? '');
        $userContext = $user ? UserContext::from($user->context)->name : null;

        $validator->sometimes('invitationable_type', "in:App\Models\\{$userContext}", function (Fluent $input) use ($userContext) {
            return ! is_null($userContext);
        });
    }

    public function attributes(): array
    {
        return [
            'email' => __('email address'),
            'role' => __('role'),
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('You must enter an email address.'),
            'email.unique' => __('This member has already been invited.'),
            'email.not_in' => __('This member already belongs to this organization.'),
            'role.required' => __('The user’s role is missing.'),
            'invitationable_type.in' => __('invitation.invited_user_has_mismatched_context'),
        ];
    }
}
