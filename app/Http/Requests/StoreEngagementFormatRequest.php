<?php

namespace App\Http\Requests;

use App\Enums\EngagementFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEngagementFormatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format' => [
                'required',
                new Enum(EngagementFormat::class),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'format' => __('engagement format'),
        ];
    }
}
