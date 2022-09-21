<?php

namespace App\Http\Requests;

use App\Enums\OrganizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SaveOrganizationRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => 'required|array',
            'roles.*' => [new Enum(OrganizationRole::class)],
        ];
    }

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'roles' => [],
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'roles.required' => __('No role has been selected. Please select at least one role and try again.'),
        ];
    }
}
