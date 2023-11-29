<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\UniqueUserEmail;
use Illuminate\Foundation\Http\FormRequest;

class SaveUserDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                new UniqueUserEmail(),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('full name'),
            'email' => __('email'),
        ];
    }
}
