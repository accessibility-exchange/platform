<?php

namespace App\Http\Requests;

use App\Models\Impact;
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
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'goals.*' => 'string|nullable',
            'scope.*' => 'string|nullable',
            'impacts' => [
                'nullable',
                'array',
                Rule::in(Impact::pluck('id')->toArray()),
            ],
            'out_of_scope.*' => 'string|nullable',
            'outcomes.*' => 'string|nullable',
            'public_outcomes' => 'boolean',
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
