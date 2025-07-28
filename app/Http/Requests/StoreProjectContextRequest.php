<?php

namespace App\Http\Requests;

use App\Enums\ProjectContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProjectContextRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'context' => ['required', new Enum(ProjectContext::class)],
            'ancestor' => 'nullable|integer|required_if:context,'.ProjectContext::FollowUp->value.'|exists:projects,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'context' => __('project context'),
            'ancestor' => __('previous project'),
        ];
    }

    public function messages(): array
    {
        return [
            'ancestor.required_if' => __('Since this is a follow-up to a previous project, you must specify the previous project.'),
        ];
    }
}
