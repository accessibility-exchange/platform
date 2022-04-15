<?php

namespace App\Http\Requests;

use App\Models\Impact;
use App\Models\Sector;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommunityMemberInterestsRequest extends FormRequest
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
            'sectors' => [
                'nullable',
                'array',
                Rule::in(Sector::pluck('id')->toArray()),
            ],
            'impacts' => [
                'nullable',
                'array',
                Rule::in(Impact::pluck('id')->toArray()),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'sectors.required' => __('You must choose at least one type of federally regulated organization.'),
            'impacts.required' => __('You must choose at least one area of impact.'),
        ];
    }
}
