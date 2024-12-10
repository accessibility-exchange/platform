<?php

namespace App\View\Components\Notification;

use App\Models\Individual;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class IndividualPublicPageNeedsUpdate extends Notification
{
    public Individual $individual;

    public function __construct(DatabaseNotification $notification)
    {
        $this->individual = Individual::find($notification->data['individual_id']);
        $this->title = __('Please review your page.');
        $this->body = __('There is some information for your new role that you will have to fill in.');
        $this->interpretation = __('Please review your page. There is some information for your new role that you will have to fill in.', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.individual-public-page-needs-update', [
            'individual' => $this->individual,
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
