<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommunityMemberExperiencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->communityMember);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lived_experience' => 'nullable|array',
            'skills_and_strengths' => 'nullable|array',
            'relevant_experiences.*.title' => 'nullable|string',
            'relevant_experiences.*.start_year' => 'nullable|required_with:work_and_volunteer_experiences.*.title|digits:4|integer|min:1900|max:' . (date('Y')),
            'relevant_experiences.*.end_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y')),
            'relevant_experiences.*.current' => 'nullable|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'work_and_volunteer_experiences.*.start_year.required_with' => __('Please provide the year you started this role.'),
        ];
    }
}
