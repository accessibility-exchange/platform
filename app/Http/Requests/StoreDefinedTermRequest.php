<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDefinedTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'term.*' => 'nullable|string|max:255|unique_translation:defined_terms',
            'definition.*' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'term' => __('term'),
            'definition' => __('definition'),
        ];
    }
}
