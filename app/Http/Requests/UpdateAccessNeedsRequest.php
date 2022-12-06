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
            'additional_needs_or_concerns' => 'nullable|integer|exists:access_supports,id',
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'general_access_needs' => [],
            'other' => 0,
            'meeting_access_needs' => [],
            'in_person_access_needs' => [],
            'document_access_needs' => [],
            'additional_needs_or_concerns' => 0,
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

    public function messages(): array
    {
        return [
            'other_access_need.required_if' => __('If you have an additional access need you must describe it.'),
        ];
    }
}
