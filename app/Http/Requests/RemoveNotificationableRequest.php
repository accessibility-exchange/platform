<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'notificationable_id' => 'required|integer',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('notificationable_id', 'exists:'.$this->input('notificationable_type').',id', function ($input) {
            return isset($input->notificationable_type) && in_array(
                $input->notificationable_type,
                [
                    'App\Models\Organization',
                    'App\Models\RegulatedOrganization',
                ]
            );
        });
    }
}
