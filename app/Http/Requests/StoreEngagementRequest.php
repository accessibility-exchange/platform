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
        $project = $this->route('project');

        return $project && $this->user()->can('update', $project);
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
            'name.en' => 'required_without:name.fr|nullable|string|max:255|unique_translation:projects',
            'name.fr' => 'required_without:name.en|nullable|string|max:255|unique_translation:projects',
            'project_id' => 'required',
            'goals.*' => 'string|nullable',
            'recruitment' => 'string|required|in:automatic,open',
        ];
    }
}
