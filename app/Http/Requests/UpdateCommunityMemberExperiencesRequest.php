<?php

namespace App\Http\Requests;

use App\Models\LivedExperience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommunityMemberExperiencesRequest extends FormRequest
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
            'lived_experiences' => [
                'nullable',
                'array'  ,
                Rule::in(LivedExperience::all()->pluck('id')->toArray()),
            ],
            'other_lived_experience' => 'nullable|string',
            'age_group' => 'nullable|string|in:youth,adult,senior',
            'living_situation' => 'nullable|string|in:urban,suburban,rural',
            'lived_experience' => 'nullable|string',
            'skills_and_strengths' => 'nullable|string',
            'work_and_volunteer_experiences.*.title' => 'nullable|string',
            'work_and_volunteer_experiences.*.start_year' => 'nullable|required_with:work_and_volunteer_experiences.*.title|digits:4|integer|min:1900|max:' . (date('Y')),
            'work_and_volunteer_experiences.*.end_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y')),
            'work_and_volunteer_experiences.*.current' => 'nullable|boolean',
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
            'work_and_volunteer_experiences.*.start_year.required_with' => __('Please provide the year you started this role.'),
        ];
    }
}
