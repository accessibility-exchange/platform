<?php

namespace App\Http\Requests;

use App\Enums\IndividualRole;
use App\Enums\OrganizationRole;
use App\Enums\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class SaveUserLanguagesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(config('locales.supported'))],
            'invitation' => 'nullable|boolean',
            'context' => [
                'nullable',
                'string',
                new Enum(UserContext::class),
                Rule::notIn([UserContext::Administrator->value]),
            ],
            'role' => [
                'nullable',
                request('context') === 'organization' ? new Enum(OrganizationRole::class) : new Enum(IndividualRole::class),
            ],
            'email' => 'nullable|email',
        ];
    }

    public function attributes(): array
    {
        return [
            'locale' => __('Website language'),
            'invitation' => __('invitation'),
            'context' => __('context'),
            'role' => __('role'),
            'email' => __('email'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
