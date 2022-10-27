<?php

namespace App\Http\Requests;

use App\Enums\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class SaveUserContextRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'context' => [
                'required',
                'string',
                new Enum(UserContext::class),
                Rule::notIn([UserContext::Administrator->value]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'context.required' => __("You must tell us who you're joining as."),
        ];
    }
}
