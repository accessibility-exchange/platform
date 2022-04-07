<?php

namespace App\Http\Requests;

use App\Models\Impact;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->user()->projectable());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $projectable_type = (string) $this->request->get('projectable_type');

        return [
            'projectable_type' => 'required|string|in:App\Models\Organization,App\Models\RegulatedOrganization',
            'projectable_id' => [
                'required',
                'integer',
                Rule::in($projectable_type::pluck('id')->toArray()),
            ],
            'ancestor_id' => [
                'nullable',
                'integer',
                Rule::in(Project::pluck('id')->toArray()),
            ],
            'name.*' => 'nullable|string|max:255|unique_translation:projects',
            'name.en' => 'required_without:name.fr|nullable|string|max:255',
            'name.fr' => 'required_without:name.en|nullable|string|max:255',
            'goals.*' => 'string|nullable',
            'goals.en' => 'required_without:goals.fr|nullable|string',
            'goals.fr' => 'required_without:goals.en|nullable|string',
            'scope.*' => 'string|nullable',
            'scope.en' => 'required_without:scope.fr|nullable|string',
            'scope.fr' => 'required_without:scope.en|nullable|string',
            'impacts' => [
                'nullable',
                'array',
                Rule::in(Impact::pluck('id')->toArray()),
            ],
            'out_of_scope.*' => 'string|nullable',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'outcomes.*' => 'string|nullable',
            'public_outcomes' => 'boolean|nullable',
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
            'name.*.required_without' => __('A project name must be provided in at least one language.'),
            'goals.*.required_without' => __('Project goals must be provided in at least one language.'),
            'scope.*.required_without' => __('Project scope must be provided in at least one language.'),
        ];
    }
}
