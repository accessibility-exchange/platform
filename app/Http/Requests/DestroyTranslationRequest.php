<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DestroyTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $translatable = $this->input('translatable_type')::where('id', $this->input('translatable_id'))->first();

        return $translatable && $this->user()->can('update', $translatable);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'language.in' => __(':model was not translatable into :language.', ['model' => $this->get('translatable_type')::find($this->get('translatable_id'))->name, 'language' => get_language_exonym($this->get('language'))]),
        ];
    }
}
