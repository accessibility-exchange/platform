<?php

namespace App\Http\Requests;

use App\Enums\ProvinceOrTerritory;
use App\Models\AccessSupport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateAccessNeedsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->context == 'individual';
    }

    public function rules(): array
    {
        return [
            'general_access_needs' => 'nullable|array',
            'general_access_needs.*' => 'exists:access_supports,id',
            'other' => 'nullable|boolean',
            'other_access_need' => 'nullable|required_if:other,true|string|max:255|exclude_unless:other,true',
            'meeting_access_needs' => 'nullable|array',
            'meeting_access_needs.*' => 'exists:access_supports,id',
            'signed_language_for_interpretation' => [
                'nullable',
                'string',
                'in:asl,lsq',
            ],
            'spoken_language_for_interpretation' => [
                'nullable',
                'string',
                Rule::in(array_keys(get_available_languages(true, false))),
            ],
            'in_person_access_needs' => 'nullable|array',
            'in_person_access_needs.*' => 'exists:access_supports,id',
            'document_access_needs' => 'nullable|array',
            'document_access_needs.*' => 'exists:access_supports,id',
            'signed_language_for_translation' => [
                'nullable',
                'string',
                'in:asl,lsq',
            ],
            'written_language_for_translation' => [
                'nullable',
                'string',
                Rule::in(array_keys(get_available_languages(true, false))),
            ],
            'street_address' => 'nullable|string',
            'unit_apartment_suite' => 'nullable|string',
            'locality' => 'nullable|string',
            'region' => [
                'nullable',
                new Enum(ProvinceOrTerritory::class),
            ],
            'postal_code' => 'nullable|string|max:7',
            'additional_needs_or_concerns' => 'nullable|exists:access_supports,id',
            'return_to_engagement' => 'nullable',
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'general_access_needs' => [],
            'other' => 0,
            'other_access_need' => null,
            'meeting_access_needs' => [],
            'in_person_access_needs' => [],
            'document_access_needs' => [],
            'additional_needs_or_concerns' => null,
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('signed_language_for_interpretation', 'required', function ($input) {
            return in_array(
                AccessSupport::where('name->en', 'Sign language interpretation')->first()->id,
                $input->meeting_access_needs ?? []
            );
        });

        $validator->sometimes('signed_language_for_interpretation', 'exclude', function ($input) {
            return ! in_array(
                AccessSupport::where('name->en', 'Sign language interpretation')->first()->id,
                $input->meeting_access_needs ?? []
            );
        });

        $validator->sometimes('spoken_language_for_interpretation', 'required', function ($input) {
            return in_array(
                AccessSupport::where('name->en', 'Spoken language interpretation')->first()->id,
                $input->meeting_access_needs ?? []
            );
        });

        $validator->sometimes('spoken_language_for_interpretation', 'exclude', function ($input) {
            return ! in_array(
                AccessSupport::where('name->en', 'Spoken language interpretation')->first()->id,
                $input->meeting_access_needs ?? []
            );
        });

        $validator->sometimes('signed_language_for_translation', 'required', function ($input) {
            return in_array(
                AccessSupport::where('name->en', 'Sign language translation')->first()->id,
                $input->document_access_needs ?? []
            );
        });

        $validator->sometimes('signed_language_for_translation', 'exclude', function ($input) {
            return ! in_array(
                AccessSupport::where('name->en', 'Sign language translation')->first()->id,
                $input->document_access_needs ?? []
            );
        });

        $validator->sometimes('written_language_for_translation', 'required', function ($input) {
            return in_array(
                AccessSupport::where('name->en', 'Written language translation')->first()->id,
                $input->document_access_needs ?? []
            );
        });

        $validator->sometimes('written_language_for_translation', 'exclude', function ($input) {
            return ! in_array(
                AccessSupport::where('name->en', 'Written language translation')->first()->id,
                $input->document_access_needs ?? []
            );
        });

        foreach ([
            'street_address',
            'locality',
            'region',
            'postal_code',
        ] as $key) {
            $validator->sometimes($key, 'required', function ($input) {
                return in_array(
                    AccessSupport::where('name->en', 'Printed version of engagement documents')->first()->id,
                    $input->document_access_needs ?? []
                );
            });
            $validator->sometimes($key, 'exclude', function ($input) {
                return ! in_array(
                    AccessSupport::where('name->en', 'Printed version of engagement documents')->first()->id,
                    $input->document_access_needs ?? []
                );
            });
        }
    }

    public function attributes(): array
    {
        return [
            'general_access_needs' => __('General access needs'),
            'general_access_needs.*' => __('General access needs'),
            'other' => __('other'),
            'other_access_need' => __('other access need'),
            'meeting_access_needs' => __('meeting access needs'),
            'meeting_access_needs.*' => __('meeting access needs'),
            'signed_language_for_interpretation' => __('Signed language for interpretation'),
            'spoken_language_for_interpretation' => __('Spoken language interpretation'),
            'in_person_access_needs' => __('in person access needs'),
            'in_person_access_needs.*' => __('in person access needs'),
            'document_access_needs' => __('document access needs'),
            'document_access_needs.*' => __('document access needs'),
            'signed_language_for_translation' => __('signed language for translation'),
            'written_language_for_translation' => __('written language for translation'),
            'street_address' => __('Street address'),
            'unit_apartment_suite' => __('Unit, apartment, or suite'),
            'locality' => __('city or town'),
            'region' => __('province or territory'),
            'postal_code' => __('Postal code'),
            'additional_needs_or_concerns' => __('Additional needs or concerns'),
            'return_to_engagement' => __('return to engagement'),
        ];
    }

    public function messages(): array
    {
        return [
            'other_access_need.required_if' => __('If you have an additional access need you must describe it.'),
        ];
    }
}
