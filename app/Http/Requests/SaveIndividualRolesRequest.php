<?php

namespace App\Http\Requests;

use App\Models\IndividualRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveIndividualRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => [
                'required',
                'array',
                Rule::in(IndividualRole::pluck('id')->toArray()),
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
