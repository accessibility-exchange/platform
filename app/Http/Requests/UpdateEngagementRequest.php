<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateEngagementRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->engagement);
    }

    public function rules(): array
    {
        return [
            'name.*' => 'nullable|string',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'description.*' => 'nullable|string',
            'description.en' => 'required_without:description.fr',
            'description.fr' => 'required_without:description.en',
            'signup_by_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.required_without' => __('An engagement name must be provided in at least one language.'),
            'description.*.required_without' => __('An engagement description must be provided in at least one language.'),
        ];
    }
}
