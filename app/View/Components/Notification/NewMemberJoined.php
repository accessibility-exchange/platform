<?php

namespace App\View\Components\Notification;

use App\Enums\TeamRole;
use App\Models\RegulatedOrganization;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class NewMemberJoined extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $accountType = $notification->data['account_type'];
        $account = $accountType::find($notification->data['account_id']);

        $accountTypeLabel = $account instanceof RegulatedOrganization
            ? Str::ucfirst(__('regulated-organization.singular_name'))
            : Str::ucfirst(__('organization.singular_name'));

        $placeholders = [
            'name' => $notification->data['member_name'],
            'account' => $account->getTranslation('name', locale()),
            'type' => $accountTypeLabel,
            'role' => TeamRole::labels()[$notification->data['team_role']],
        ];

        if ($account->isPublishable()) {
            $this->body = safe_markdown(
                ':name has joined [:account](:account_url) (:type) with the role of :role.',
                $placeholders + ['account_url' => localized_route($account->getRoutePrefix().'.show', $account)],
            );
        } else {
            $this->body = __(':name has joined :account (:type) with the role of :role.', $placeholders);
        }

        $this->title = __('New member joined');
        $this->interpretation = __('New member joined', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.new-member-joined', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
