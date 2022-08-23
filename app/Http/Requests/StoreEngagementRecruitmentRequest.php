<?php

namespace App\Http\Requests;

use App\Enums\EngagementRecruitment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEngagementRecruitmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recruitment' => [
                'required',
                new Enum(EngagementRecruitment::class),
            ],
        ];
    }
}
