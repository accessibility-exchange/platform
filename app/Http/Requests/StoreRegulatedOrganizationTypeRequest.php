<?php

namespace App\Http\Requests;

use App\Enums\RegulatedOrganizationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegulatedOrganizationTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::enum(RegulatedOrganizationType::class),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => __('organization type'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'type.required' => __('You must select what type of organization you are.'),
        ];
    }
}
