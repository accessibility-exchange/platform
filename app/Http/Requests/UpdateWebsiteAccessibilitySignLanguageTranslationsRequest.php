<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteAccessibilitySignLanguageTranslationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sign_language_translations' => 'required|boolean',
            'target' => 'required|url',
        ];
    }
}
