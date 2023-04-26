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
        $fallbacks = [
            'roles' => [],
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'roles.required' => __('You must select what you would like to do on the website.'),
        ];
    }
}
