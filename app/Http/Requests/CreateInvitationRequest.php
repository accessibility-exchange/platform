<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invitationable = $this->input('invitationable_type')::where('id', $this->input('invitationable_id'))->first();

        return $invitationable && $this->user()->can('update', $invitationable);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $invitationable_type = $this->input('invitationable_type');
        $invitationable_id = $this->input('invitationable_id');

        return [
            'email' => ['required', 'email', Rule::unique('invitations')->where(function ($query) use ($invitationable_type, $invitationable_id) {
                $query
                    ->where('invitationable_type', $invitationable_type)
                    ->where('invitationable_id', $invitationable_id);
            })],
            'role' => ['required', 'string', Rule::in(config('hearth.organizations.roles'))],
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
        $invitationable = $this->input('invitationable_type')::where('id', $this->input('invitationable_id'))->first();

        $validator->after(function ($validator) use ($invitationable) {
            $validator->errors()->addIf(
                $invitationable->hasUserWithEmail($this->email),
                'email',
                __('invitation.invited_user_already_belongs_to_team')
            );
        })->validateWithBag('inviteMember');
    }
}
