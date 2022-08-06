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

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'sectors' => [],
            'impacts' => [],
        ]);
    }
}
