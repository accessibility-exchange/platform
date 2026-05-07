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
        $placeholders = [
            'name' => $notification->data['user_name'],
            'context' => UserContext::labels()[$notification->data['user_context']],
        ];

        if (isset($notification->data['account_id'])) {
            $accountType = $notification->data['account_type'];
            $account = $accountType::find($notification->data['account_id']);
            $placeholders['account'] = $account->getTranslation('name', locale());

            if ($account->isPublishable()) {
                $this->body = safe_markdown(
                    ':name from [:account](:account_url) (:context) has deleted their account.',
                    $placeholders + ['account_url' => localized_route($account->getRoutePrefix().'.show', $account)],
                );
            } else {
                $this->body = __(':name from :account (:context) has deleted their account.', $placeholders);
            }
        } else {
            $this->body = __(':name (:context) has deleted their account.', $placeholders);
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
