<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIndividualExperiencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->individual);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lived_experience' => 'nullable|array:'.implode(',', $this->individual->languages),
            'skills_and_strengths' => 'nullable|array:'.implode(',', $this->individual->languages),
            'relevant_experiences.*.title' => 'nullable|required_with:relevant_experiences.*.organization,relevant_experiences.*.start_year,relevant_experiences.*.end_year,relevant_experiences.*.current|string',
            'relevant_experiences.*.organization' => 'nullable|required_with:relevant_experiences.*.title|string',
            'relevant_experiences.*.start_year' => 'nullable|required_with:relevant_experiences.*.title|digits:4|integer|min:1900|max:'.(date('Y')),
            'relevant_experiences.*.end_year' => 'nullable|required_without:relevant_experiences.*.current|prohibits:relevant_experiences.*.current|digits:4|integer|min:1900|gte:relevant_experiences.*.start_year|max:'.(date('Y')),
            'relevant_experiences.*.current' => 'nullable|required_without:relevant_experiences.*.end_year|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'relevant_experiences.*.title' => __('Title of Role'),
            'relevant_experiences.*.organization' => __('Name of Organization'),
            'relevant_experiences.*.start_year' => __('Start Year'),
            'relevant_experiences.*.end_year' => __('End Year'),
            'relevant_experiences.0.current' => __('I currently work or volunteer here'),
        ];
    }

    public function messages(): array
    {
        return [
            'relevant_experiences.*.end_year.gte' => __('Please enter an end year for your experience that is equal to or greater than the start year.'),
        ];
    }
}
