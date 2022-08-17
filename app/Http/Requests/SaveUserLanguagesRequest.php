<?php

namespace App\Http\Requests;

use App\Enums\IndividualRole;
use App\Enums\OrganizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class SaveUserLanguagesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(config('locales.supported', ['en', 'fr']))],
            'signed_language' => 'nullable|string|in:ase,fcs',
            'invitation' => 'nullable|boolean',
            'context' => [
                'nullable',
                'string',
                Rule::in(config('app.contexts')),
            ],
            'role' => [
                'nullable',
                request('context') === 'organization' ? new Enum(OrganizationRole::class) : new Enum(IndividualRole::class),
            ],
            'email' => 'nullable|email',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
