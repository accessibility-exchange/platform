<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveNotificationableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('receiveNotifications');
    }

    public function rules(): array
    {
        return [
            'notificationable_type' => 'required|string|in:App\Models\Organization,App\Models\RegulatedOrganization',
            'notificationable_id' => 'required|integer|exists:' . $this->input('notificationable_type') . ',id',
        ];
    }
}
