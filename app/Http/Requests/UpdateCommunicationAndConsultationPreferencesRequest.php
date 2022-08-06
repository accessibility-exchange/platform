<?php

namespace App\Http\Requests;

use App\Enums\MeetingType;
use App\Models\ConsultingMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateCommunicationAndConsultationPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->context == 'individual';
    }

    public function rules(): array
    {
        return [
            'preferred_contact_person' => 'required|in:me,support-person',
            'email' => 'nullable|email',
            'phone' => 'required_if:vrs,true|nullable|phone:CA',
            'vrs' => 'nullable|boolean',
            'support_person_name' => 'required_if:preferred_contact_person,support-person|nullable|string|exclude_if:preferred_contact_person,me',
            'support_person_email' => 'nullable|email',
            'support_person_phone' => 'required_if:support_person_vrs,true|nullable|phone:CA|exclude_if:preferred_contact_person,me',
            'support_person_vrs' => 'nullable|boolean|exclude_if:preferred_contact_person,me',
            'preferred_contact_method' => 'required|in:email,phone',
            'consulting_methods' => [
                'nullable',
                'array',
                'min:1',
                Rule::requiredIf(request()->user()->individual->isParticipant()),
            ],
            'consulting_methods.*' => 'exists:consulting_methods,id',
            'meeting_types' => 'nullable|array',
            'meeting_types.*' => [new Enum(MeetingType::class)],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('preferred_contact_method', 'in:email', function ($input) {
            return $input->preferred_contact_person == 'me' && ! is_null($input->email) && is_null($input->phone) ||
                $input->preferred_contact_person == 'support-person' && ! is_null($input->support_person_email) && is_null($input->support_person_phone);
        });

        $validator->sometimes('preferred_contact_method', 'in:phone', function ($input) {
            return  $input->preferred_contact_person == 'me' && is_null($input->email) && ! is_null($input->phone) ||
                $input->preferred_contact_person == 'support-person' && is_null($input->support_person_email) && ! is_null($input->support_person_phone);
        });

        $validator->sometimes('meeting_types', 'required', function ($input) {
            $interviews = ConsultingMethod::where('name->en', 'Interviews')->first()->id;
            $focusGroups = ConsultingMethod::where('name->en', 'Focus groups')->first()->id;
            $workshops = ConsultingMethod::where('name->en', 'Workshops')->first()->id;

            return in_array($interviews, $input->consulting_methods ?? []) || in_array($focusGroups, $input->consulting_methods ?? []) || in_array($workshops, $input->consulting_methods ?? []);
        });
    }

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'consulting_methods' => [],
            'meeting_types' => [],
        ]);
    }

    public function attributes(): array
    {
        return [
            'email' => __('email address'),
            'phone' => __('phone number'),
            'contact_person_email' => __('email address'),
            'contact_person_phone' => __('phone number'),
        ];
    }

    public function messages(): array
    {
        return [
            'support_person_name.required_if' => __('Your support person’s name is required if they are your preferred contact person.'),
        ];
    }
}
