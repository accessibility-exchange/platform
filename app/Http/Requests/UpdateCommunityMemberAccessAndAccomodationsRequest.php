<?php

namespace App\Http\Requests;

use App\Models\AccessSupport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommunityMemberAccessAndAccomodationsRequest extends FormRequest
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
            'access_needs' => [
                'nullable',
                'array',
                Rule::in(AccessSupport::all()->pluck('id')->toArray()),
            ],
            'meeting_types' => 'required|array|min:1|in:in_person,web_conference,phone',
        ];
    }
}
