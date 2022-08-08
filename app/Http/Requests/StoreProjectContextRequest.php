<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectContextRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'context' => 'required|string|in:new,follow-up',
            'ancestor' => 'nullable|integer|required_if:context,follow-up,exists:projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ancestor.required_if' => __('Since this is a follow-up to a previous project, you must specify the previous project.'),
        ];
    }
}
