<?php

namespace App\Http\Requests;

use App\Enums\NotificationChannel;
use App\Enums\NotificationMethod;
use App\Enums\OrganizationNotificationChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'preferred_notification_method' => [
                Rule::requiredIf(in_array($user->context, ['individual', 'organization'])),
                new Enum(NotificationMethod::class),
            ],
            'notification_settings.consultants.channels' => [
                'nullable',
                'array',
            ],
            'notification_settings.consultants.channels.*' => [
                new Enum(NotificationChannel::class),
            ],
            'notification_settings.connectors.channels' => [
                'nullable',
                'array',
            ],
            'notification_settings.connectors.channels.*' => [
                new Enum(NotificationChannel::class),
            ],
            'notification_settings.reports.channels' => [
                'nullable',
                'array',
            ],
            'notification_settings.reports.channels.*' => [
                new Enum(NotificationChannel::class),
            ],
            'notification_settings.projects.channels' => [
                'nullable',
                'array',
            ],
            'notification_settings.projects.channels.*' => [
                $user->context === 'individual' ? new Enum(NotificationChannel::class) : new Enum(OrganizationNotificationChannel::class),
            ],
            'notification_settings.projects.creators' => [
                'nullable',
                'exclude_without:notification_settings.projects.channels',
                'required_with:notification_settings.projects.channels',
                'array',
            ],
            'notification_settings.projects.creators.*' => 'in:organizations,regulated-organizations',
            'notification_settings.projects.types' => [
                'nullable',
                'exclude_without:notification_settings.projects.channels',
                'required_with:notification_settings.projects.channels',
                'array',
            ],
            'notification_settings.projects.types.*' => $user->context === 'individual' ? 'in:lived-experience,of-interest' : 'in:constituents',
            'notification_settings.projects.engagements' => [
                'nullable',
                'exclude_without:notification_settings.projects.channels',
                'required_with:notification_settings.projects.channels',
                'array',
            ],
            'notification_settings.projects.engagements.*' => $user->context === 'individual' ? 'in:lived-experience,of-interest' : 'in:constituents',
            'notification_settings.updates.channels' => [
                'nullable',
                'array',
            ],
            'notification_settings.updates.channels.*' => [
                new Enum(NotificationChannel::class),
            ],
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'notification_settings.consultants.channels' => [],
            'notification_settings.connectors.channels' => [],
            'notification_settings.reports.channels' => [],
            'notification_settings.projects.channels' => [],
            'notification_settings.projects.creators' => [],
            'notification_settings.projects.types' => [],
            'notification_settings.projects.engagements' => [],
            'notification_settings.updates.channels' => [],
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);
    }

    public function messages(): array
    {
        return [
            'notification_settings.projects.creators.required_with' => __('You must choose at least one type of organization.'),
            'notification_settings.projects.types.required_with' => __('You must choose at least one type of project.'),
            'notification_settings.projects.engagements.required_with' => __('You must choose at least one type of engagement.'),
        ];
    }
}
