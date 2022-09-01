<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEngagementLanguagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'languages' => 'required|array|min:1',
            'languages.*' => [
                Rule::in(array_keys(get_available_languages())),
            ],
        ];
    }
}
