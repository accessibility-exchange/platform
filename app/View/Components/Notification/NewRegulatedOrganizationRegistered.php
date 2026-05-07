<?php

namespace App\View\Components\Notification;

use App\Enums\UserContext;
use App\Models\RegulatedOrganization;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class NewRegulatedOrganizationRegistered extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $regulatedOrganization = RegulatedOrganization::find($notification->data['regulated_organization_id']);

        $placeholders = [
            'regulated_organization' => $regulatedOrganization->getTranslation('name', locale()),
            'name' => $notification->data['creator_name'],
            'context' => UserContext::labels()[$notification->data['user_context']],
        ];

        if ($regulatedOrganization->isPublishable()) {
            $this->body = safe_markdown(
                'A new :context, [:regulated_organization](:regulated_organization_url), has been registered by :name.',
                $placeholders + ['regulated_organization_url' => localized_route($regulatedOrganization->getRoutePrefix().'.show', $regulatedOrganization)],
            );
        } else {
            $this->body = __('A new :context, :regulated_organization, has been registered by :name.', $placeholders);
        }

        $this->title = __('New regulated organization registered');
        $this->interpretation = __('New regulated organization registered', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.new-regulated-organization-registered', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
