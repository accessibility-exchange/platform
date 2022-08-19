<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateEngagementRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->project);
    }

    public function rules(): array
    {
        return [
            'description.*' => 'nullable|string',
            'description.en' => 'required_without:description.fr',
            'description.fr' => 'required_without:description.en',
            'signup_by_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'description.*.required_without' => __('An engagement description must be provided in at least one language.'),
        ];
    }
}
