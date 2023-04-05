<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $translatable = $this->input('translatable_type')::where('id', $this->input('translatable_id'))->first();

        return $translatable && $this->user()->can('update', $translatable);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'translatable_type' => 'required|string',
            'translatable_id' => 'exists:'.$this->get('translatable_type').',id',
            'new_language' => [
                'required',
                'string',
                Rule::notIn($this->get('translatable_type')::find($this->get('translatable_id'))->languages),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'new_language.not_in' => __(':model is already translatable into :language.', ['model' => $this->get('translatable_type')::find($this->get('translatable_id'))->name, 'language' => get_language_exonym($this->get('new_language'))]),
        ];
    }
}
