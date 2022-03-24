<?php

namespace App\Http\Requests;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEngagementRequest extends FormRequest
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
        $engagement = $this->route('engagement');

        return [
            'name.*' => [
                'required',
                'string',
                'max:255',
                UniqueTranslationRule::for('engagements')->ignore($engagement->id),
            ],
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
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
