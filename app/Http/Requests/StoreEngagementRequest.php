<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Worksome\RequestFactories\Concerns\HasFactory;

class StoreEngagementRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->project);
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:App\Models\Project,id',
            'name.*' => 'nullable|string|max:255|unique_translation:engagements',
            'name.en' => 'required_without:name.fr|nullable|string|max:255',
            'name.fr' => 'required_without:name.en|nullable|string|max:255',
            'who' => 'required|in:individuals,organization',
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.unique_translation' => __('An engagement with this name already exists.'),
            'name.*.required_without' => __('An engagement name must be provided in at least one language.'),
            'who.required' => __('You must indicate who you want to engage.'),
        ];
    }
}
