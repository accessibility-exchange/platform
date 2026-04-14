<?php

namespace App\View\Components\Notification;

use App\Enums\UserContext;
use App\Models\Organization;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class NewOrganizationRegistered extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $organization = Organization::find($notification->data['organization_id']);

        $placeholders = [
            'organization' => $organization->getTranslation('name', locale()),
            'name' => $notification->data['creator_name'],
            'context' => UserContext::labels()[$notification->data['user_context']],
        ];

        if ($organization->isPublishable()) {
            $this->body = safe_markdown(
                'A new :context, [:organization](:organization_url), has been registered by :name.',
                $placeholders + ['organization_url' => localized_route($organization->getRoutePrefix().'.show', $organization)],
            );
        } else {
            $this->body = __('A new :context, :organization, has been registered by :name.', $placeholders);
        }

        $this->title = __('New organization registered');
        $this->interpretation = __('New organization registered', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.new-organization-registered', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
