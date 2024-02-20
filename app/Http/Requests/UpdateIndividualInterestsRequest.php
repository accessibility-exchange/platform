<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIndividualInterestsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->individual);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
}
