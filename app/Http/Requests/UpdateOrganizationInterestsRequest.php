<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationInterestsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'impacts' => 'nullable|array',
            'impacts.*' => 'exists:impacts,id',
            'sectors' => 'nullable|array',
            'sectors.*' => 'exists:sectors,id',
        ];
    }
}
