<?php

namespace App\Http\Requests;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateResourceCollectionRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'max:255',
                UniqueTranslationRule::for('resource_collections'),
            ],
            'description' => 'required|string',
            'user_id' => 'required',
            'resource_ids' => 'array|nullable',
            'resource_ids.*' => 'exists:resources,id',
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
            'title.unique' => __('validation.custom.resource_collection.title_exists'),
        ];
    }
}
