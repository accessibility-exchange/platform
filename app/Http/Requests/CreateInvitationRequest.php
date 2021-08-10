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
        $inviteable = $this->input('inviteable_type')::where('id', $this->input('inviteable_id'))->first();

        return $inviteable && $this->user()->can('update', $inviteable);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $inviteable_type = $this->input('inviteable_type');
        $inviteable_id = $this->input('inviteable_id');

        return [
            'email' => ['required', 'email', Rule::unique('invitations')->where(function ($query) use ($inviteable_type, $inviteable_id) {
                $query
                    ->where('inviteable_type', $inviteable_type)
                    ->where('inviteable_id', $inviteable_id);
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
        $inviteable = $this->input('inviteable_type')::where('id', $this->input('inviteable_id'))->first();

        $validator->after(function ($validator) use ($inviteable) {
            $validator->errors()->addIf(
                $inviteable->hasUserWithEmail($this->email),
                'email',
                __('invitation.invited_user_already_belongs_to_team')
            );
        })->validateWithBag('inviteMember');

        return;
    }
}
