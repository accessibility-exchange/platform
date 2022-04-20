<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommunityMemberCommunicationAndMeetingPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->communityMember);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required_without:phone|nullable|email',
            'phone' => 'required_without:email|nullable|string',
            'vrs' => 'prohibited_unless:phone|nullable|boolean',
            'support_people.*.name' => 'nullable|string',
            'support_people.*.email' => 'exclude_without:support_people.*.name|nullable|email|required_without:support_people.*.phone',
            'support_people.*.phone' => 'exclude_without:support_people.*.name|nullable|string|required_without:support_people.*.email',
            'support_people.*.page_creator' => 'nullable|boolean',
            'preferred_contact_method' => [
                'required_with_all:email,phone',
                'required_with_all:support_people.*.email,support_people.*.phone',
                'in:email,phone,vrs',
            ],
            'preferred_contact_person' => 'required_with:support_people.*.name',
            'meeting_types' => 'required|array|min:1|in:in_person,web_conference,phone',
        ];
    }
}
