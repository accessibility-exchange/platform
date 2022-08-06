<?php

namespace App\Http\Requests;

use App\Models\Individual;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectTeamRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_size' => 'nullable|string',
            'team_has_disability_or_deaf_lived_experience' => 'nullable|boolean',
            'team_has_other_lived_experience' => 'nullable|boolean',
            'team_languages' => 'required|array|min:1',
            'contacts.*.name' => 'nullable|string',
            'contacts.*.email' => 'nullable|email|required_with:contacts.*.name',
            'contacts.*.phone' => 'nullable|string|required_with:contacts.*.name',
            'has_consultant' => 'required|boolean',
            'individual_consultant_id' => [
                'exclude_unless:has_consultant,true',
                'exclude_unless:consultant_origin,platform',
                'required',
                Rule::in(Individual::pluck('id')->toArray()),
            ],
            'consultant_name' => 'exclude_unless:has_consultant,true|exclude_unless:consultant_origin,external|required|string',
            'consultant_responsibilities.*' => 'exclude_unless:has_consultant,true|nullable|string',
            'consultant_responsibilities.en' => 'exclude_unless:has_consultant,true|nullable|string',
            'consultant_responsibilities.fr' => 'exclude_unless:has_consultant,true|nullable|string',
            'team_trainings.*.name' => 'nullable|string',
            'team_trainings.*.date' => 'nullable|date|required_with:trainings.*.name',
            'team_trainings.*.trainer_name' => 'nullable|string|required_with:trainings.*.name',
            'team_trainings.*.trainer_url' => 'nullable|url|required_with:trainings.*.name',
        ];
    }
}
