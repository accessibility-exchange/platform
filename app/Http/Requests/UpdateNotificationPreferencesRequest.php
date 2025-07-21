<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = request()->user();

        return [
            'notification_settings.engagements' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'notification_settings.engagements' => __('engagements notification setting'),
        ];
    }
}
