<?php

namespace App\Http\Requests;

use App\Enums\IdentityCluster;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateIdentityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name.*' => [
                'nullable',
                'string',
                'max:255',
                UniqueTranslationRule::for('identities')->ignore($this->identities->id),
            ],
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
