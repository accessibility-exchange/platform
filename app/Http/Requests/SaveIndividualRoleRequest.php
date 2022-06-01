<?php

namespace App\Http\Requests;

use App\Models\IndividualRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveIndividualRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'roles' => [
                'required',
                'array',
                Rule::in(IndividualRole::pluck('id')->toArray()),
            ],
        ];
    }
}
