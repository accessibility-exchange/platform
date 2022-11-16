<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_size' => 'nullable|array',
            'team_size.*' => 'nullable|string',
            'team_has_disability_or_deaf_lived_experience' => 'nullable|boolean',
            'team_trainings' => 'nullable|array',
            'team_trainings.*.name' => 'required|string',
            'team_trainings.*.date' => 'required|date',
            'team_trainings.*.trainer_name' => 'required|string',
            'team_trainings.*.trainer_url' => 'required|active_url',
            'contact_person_name' => 'required|string',
            'contact_person_email' => 'nullable|email|required_without:contact_person_phone|required_if:preferred_contact_method,email',
            'contact_person_phone' => 'nullable|phone:CA|required_without:contact_person_email|required_if:preferred_contact_method,phone',
            'contact_person_vrs' => 'nullable|boolean',
            'preferred_contact_method' => 'required|in:email,phone',
            'contact_person_response_time' => 'required|array',
            'contact_person_response_time.en' => 'required_without:contact_person_response_time.fr|nullable|string',
            'contact_person_response_time.fr' => 'required_without:contact_person_response_time.en|nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'team_trainings.*.name' => __('training name'),
            'team_trainings.*.date' => __('training date'),
            'team_trainings.*.trainer_name' => __('training organization or trainer name'),
            'team_trainings.*.trainer_url' => __('training organization or trainer website address'),
        ];
    }

    public function messages(): array
    {
        return [
            'contact_person_response_time.*.required_without' => __('An approximate response time must be provided in at least one language.'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->contact_person_vrs && ! $this->contact_person_phone) {
                $validator->errors()->add(
                    'contact_person_phone',
                    __('Since you have indicated that your contact person needs VRS, please enter a phone number.')
                );
            }
        });
    }

    public function prepareForValidation()
    {
        $this->merge([
            'team_trainings' => array_map(function ($training) {
                $training['trainer_url'] = normalize_url($training['trainer_url']);

                return $training;
            }, $this->team_trainings ?? []),
        ]);
    }
}
