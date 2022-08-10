<?php

namespace App\Http\Requests;

use App\Models\OrganizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveOrganizationRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => [
                'nullable',
                'array',
                Rule::in(OrganizationRole::pluck('id')->toArray()),
            ],
        ];
    }

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'roles' => [],
        ]);
    }
}
