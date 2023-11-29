<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DestroyTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! is_callable($this->input('translatable_type').'::where')) {
            return false;
        }

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
            'language' => [
                'required',
                'string',
                Rule::in($this->get('translatable_type')::find($this->get('translatable_id'))->languages),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'translatable_type' => __('translatable type'),
            'translatable_id' => __('translatable id'),
            'language' => __('language'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'language.required' => __('Please select a language to remove.'),
            'language.string' => __('Please select a language to remove.'),
            'language.in' => __(':model was not translatable into :language.', ['model' => $this->get('translatable_type')::find($this->get('translatable_id'))->name, 'language' => get_language_exonym($this->get('language') ?? '')]),
        ];
    }
}
