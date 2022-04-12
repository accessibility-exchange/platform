<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserLanguagesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(config('locales.supported', ['en', 'fr']))],
            'signed_language' => 'nullable|string|in:ase,fcs',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
