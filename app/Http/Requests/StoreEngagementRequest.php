<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEngagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name.*' => 'nullable|string|max:255|unique_translation:engagements',
            'name.en' => 'required_without:name.fr|nullable|string|max:255|unique_translation:engagements',
            'name.fr' => 'required_without:name.en|nullable|string|max:255|unique_translation:engagements',
            'project_id' => 'required',
            'goals.*' => 'string|nullable',
            'recruitment' => 'string|required|in:automatic,open',
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
            'name.*.unique_translation' => __('An engagement with this name already exists.'),
            'name.*.required_without' => __('An engagement name field must be provided in at least one language.'),
        ];
    }
}
