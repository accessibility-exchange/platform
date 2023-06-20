<?php

namespace App\Http\Requests;

use App\Enums\TeamRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'role' => ['required', new Enum(TeamRole::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('The user’s email address is missing.'),
            'email.not_in' => __('This user already belongs to this team.'),
            'role.required' => __('The user’s role is missing.'),
        ];
    }
}
