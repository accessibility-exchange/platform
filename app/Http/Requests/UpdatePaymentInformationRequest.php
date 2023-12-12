<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->context == 'individual';
    }

    public function rules(): array
    {
        return [
            'payment_types' => 'nullable|required_unless:other,true|array',
            'payment_types.*' => 'exists:payment_types,id',
            'other' => 'nullable|boolean',
            'other_payment_type' => 'nullable|required_if:other,true|string|max:255|exclude_unless:other,true',
        ];
    }

    public function attributes(): array
    {
        return [
            'payment_types' => __('Payment type'),
            'payment_types.*' => __('Payment type'),
            'other' => __('Other'),
            'other_payment_type' => __('other payment type'),
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'payment_types' => [],
            'other' => 0,
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function messages(): array
    {
        return [
            'payment_types.required_unless' => __('You must choose at least one payment type.'),
            'other_payment_type.required' => __('The other payment type must be specified.'),
        ];
    }
}
