<?php

namespace App\Http\Requests;

use App\Enums\Themes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateWebsiteAccessibilityPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme' => ['required', new Enum(Themes::class)],
            'text_to_speech' => 'required|boolean',
            'sign_language_translations' => 'nullable|string|in:ase,fcs',
        ];
    }
}
