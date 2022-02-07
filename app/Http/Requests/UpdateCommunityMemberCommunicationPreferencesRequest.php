<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommunityMemberCommunicationPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $communityMember = $this->route('communityMember');

        return $communityMember && $this->user()->can('update', $communityMember);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'phone' => 'required|string',
            'support_people.*.name' => 'nullable|string',
            'support_people.*.email' => 'nullable|email|required_with:support_people.*.name',
            'support_people.*.phone' => 'nullable|string|required_with:support_people.*.name',
            'support_people.*.page_creator' => 'nullable|boolean',
            'preferred_contact_methods' => 'required|array|min:1',
            'languages' => 'required|array|min:1',
        ];
    }
}
