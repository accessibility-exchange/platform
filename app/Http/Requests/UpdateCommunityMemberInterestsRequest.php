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
            'areas_of_interest' => 'nullable|string',
            'sectors' => [
                'nullable',
                'array',
                Rule::in(Sector::all()->pluck('id')->toArray()),
            ],
            'impacts' => [
                'nullable',
                'array',
                Rule::in(Impact::all()->pluck('id')->toArray()),
            ],
            'service_preference' => 'nullable|array|in:digital,non-digital',
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
            'sectors.required' => __('You must choose at least one type of regulated entity.'),
            'impacts.required' => __('You must choose at least one area of impact.'),
        ];
    }
}
