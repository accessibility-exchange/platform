<?php

namespace App\View\Components\Notification;

use App\Enums\UserContext;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class UserAccountDeleted extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $contextLabel = UserContext::labels()[$notification->data['user_context']];

        if (isset($notification->data['organization_id'])) {
            $organizationType = $notification->data['organization_type'];
            $organization = $organizationType::find($notification->data['organization_id']);

            $this->body = __(':name (:context) from :organization has deleted their account.', [
                'name' => $notification->data['user_name'],
                'context' => $contextLabel,
                'organization' => $organization->getTranslation('name', locale()),
            ]);
        } else {
            $this->body = __(':name (:context) has deleted their account.', [
                'name' => $notification->data['user_name'],
                'context' => $contextLabel,
            ]);
        }

        $this->title = __('User account deleted');
        $this->interpretation = __('User account deleted', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.user-account-deleted', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
