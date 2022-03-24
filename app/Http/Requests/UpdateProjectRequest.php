<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $project = $this->route('project');

        return [
            'name.*' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Project::class)->ignore($project->id),
            ],
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'start_date' => 'required|date',
            'end_date' => 'date|nullable',
            'goals' => 'string|nullable',
            'impact' => 'string|nullable',
            'out_of_scope' => 'string|nullable',
            'virtual_consultation' => 'boolean',
            'timeline' => 'string|nullable',
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
            'name.*.unique_translation' => __('A project with this name already exists.'),
            'name.*.required_without' => __('A project name field must be provided in at least one language.'),
        ];
    }
}
