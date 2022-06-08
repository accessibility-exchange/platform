<?php

namespace App\Http\Requests;

use App\Models\OrganizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationRolesRequest extends FormRequest
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
                Rule::in(OrganizationRole::pluck('id')->toArray()),
            ],
        ];
    }
}
