<?php

namespace App\View\Components\Notification;

use App\Models\Organization;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class OrganizationPageNeedsUpdate extends Notification
{
    public Organization $organization;

    public function __construct(DatabaseNotification $notification)
    {
        $this->organization = Organization::find($notification->data['organization_id']);
        $this->title = __('Please review your page.');
        $this->body = __('There is some information for your new role that you will have to fill in.');
        $this->interpretation = __('Please review your page. There is some information for your new role that you will have to fill in.', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.organization-page-needs-update', [
            'organization' => $this->organization,
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
