<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'team_languages' => 'required|array|min:1',
            'team_languages.*' => [
                'nullable',
                Rule::in(array_keys(get_available_languages(true))),
            ],
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->contact_person_vrs && ! $this->contact_person_phone) {
                $validator->errors()->add(
                    'contact_person_phone',
                    __('Since the checkbox for your contact person requiring VRS for phone calls is checked, you must enter a phone number.')
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
