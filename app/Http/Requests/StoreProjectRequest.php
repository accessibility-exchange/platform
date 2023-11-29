<?php

namespace App\Http\Requests;

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
        return $this->user()->can('update', $this->user()->projectable);
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
                'exists:App\Models\Project,id',
            ],
            'name.*' => 'nullable|string|max:255|unique_translation:projects',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
        ];
    }

    public function attributes(): array
    {
        return [
            'projectable_type' => __('projectable type'),
            'projectable_id' => __('projectable id'),
            'ancestor_id' => __('previous project id'),
            'name' => __('project name'),
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
