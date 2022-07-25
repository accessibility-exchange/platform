<?php

namespace App\Http\Requests;

use App\Enums\NotificationMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateNotificationPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preferred_notification_method' => ['required', new Enum(NotificationMethods::class)],
        ];
    }
}
