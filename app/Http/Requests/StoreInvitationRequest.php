<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
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
            // TODO: Clarify these roles, move to Enum.
            'role' => ['required', 'string', Rule::in(config('hearth.organizations.roles'))],
        ];
    }

    public function messages(): array
    {
        return [
            'email.not_in' => __('This user already belongs to this team.'),
        ];
    }
}
