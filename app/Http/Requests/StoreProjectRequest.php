<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Worksome\RequestFactories\Concerns\HasFactory;

class StoreProjectRequest extends FormRequest
{
    use HasFactory;

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
                Rule::in(class_exists($projectable_type) ? $projectable_type::pluck('id')->toArray() : []),
            ],
            'ancestor_id' => [
                'nullable',
                'integer',
                'exists:App\Models\Project,id',
            ],
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'name.*' => 'nullable|string|max:255|unique_translation:projects',
        ];
    }

    public function attributes(): array
    {
        return [
            'projectable_type' => __('projectable type'),
            'projectable_id' => __('projectable id'),
            'ancestor_id' => __('previous project id'),
            'name.en' => __('project name (English)'),
            'name.fr' => __('project name (French)'),
            'name.*' => __('project name'),
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
        ];
    }
}
