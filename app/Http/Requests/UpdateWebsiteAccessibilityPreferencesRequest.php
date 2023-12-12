<?php

namespace App\Http\Requests;

use App\Enums\Theme;
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
            'theme' => ['required', new Enum(Theme::class)],
            'text_to_speech' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'theme' => __('Contrast adjustment'),
            'text_to_speech' => __('Text to speech'),
        ];
    }
}
