<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguagePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale' => [
                'required',
                Rule::in(config('locales.supported')),
            ],
            'first_language' => [
                'nullable',
                Rule::requiredIf($this->user()->context === 'individual'),
                'string',
                Rule::in(array_keys(get_available_languages(true))),
            ],
            'working_languages' => [
                'nullable',
                'array',
                Rule::requiredIf($this->user()->context === 'individual'),
            ],
            'working_languages.*' => [
                Rule::in(array_keys(get_available_languages(true))),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'working_languages.*.in' => __('The selected working language is not valid.'),
        ];
    }
}
