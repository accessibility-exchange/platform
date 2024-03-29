<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreasOfInterestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->context == 'individual';
    }

    public function rules(): array
    {
        return [
            'sectors' => 'nullable|array',
            'sectors.*' => 'exists:sectors,id',
            'impacts' => 'nullable|array',
            'impacts.*' => 'exists:impacts,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'sectors' => __('Regulated Organization type'),
            'sectors.*' => __('Regulated Organization type'),
            'impacts' => __('area of accessibility planning and design'),
            'impacts.*' => __('area of accessibility planning and design'),
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'sectors' => [],
            'impacts' => [],
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }
}
