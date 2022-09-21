<?php

namespace App\Http\Requests;

use App\Enums\IndividualRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SaveIndividualRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => 'required|array',
            'roles.*' => [new Enum(IndividualRole::class)],
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
            'roles.required' => __('Select at least one role.'),
        ];
    }
}
