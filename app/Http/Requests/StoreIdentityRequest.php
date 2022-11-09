<?php

namespace App\Http\Requests;

use App\Enums\IdentityCluster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreIdentityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.*' => 'nullable|string|max:255|unique_translation:identities',
            'name.en' => 'required',
            'name.fr' => 'required',
            'description.*' => 'nullable|string',
            'cluster' => [
                'nullable',
                'string',
                new Enum(IdentityCluster::class),
            ],
        ];
    }
}
