<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $entity = $this->route('entity');

        return $entity && $this->user()->can('update', $entity);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name.*' => 'nullable|string|max:255|unique_translation:projects',
            'name.en' => 'required|string|max:255|unique_translation:projects',
            'start_date' => 'required|date',
            'end_date' => 'date|nullable',
            'entity_id' => 'required',
            'goals' => 'string|nullable',
            'impact' => 'string|nullable',
            'out_of_scope' => 'string|nullable',
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
            'name.unique' => __('A project with this name already exists.'),
        ];
    }
}
