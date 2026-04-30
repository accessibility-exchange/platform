<?php

namespace App\View\Components\Notification;

use App\Enums\UserContext;
use App\Models\User;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class NewUserRegistered extends Notification
{
    public bool $showManageAccountsAction = false;

    public function __construct(DatabaseNotification $notification)
    {
        $user = User::find($notification->data['user_id']);
        $individual = $user->individual;

        if ($individual) {
            $this->showManageAccountsAction = true;
        }

        $placeholders = [
            'name' => $user->name,
            'email' => $user->email,
            'context' => UserContext::labels()[$user->context],
        ];

        if ($individual && $individual->isPublishable()) {
            $this->body = safe_markdown(
                '[:name](:url) (:email) registered as a new :context user.',
                $placeholders + ['url' => localized_route('individuals.show', $individual)],
            );
        } else {
            $this->body = __(':name (:email) registered as a new :context user.', $placeholders);
        }

        $this->title = __('New user registered');
        $this->interpretation = __('New user registered', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.new-user-registered', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'showManageAccountsAction' => $this->showManageAccountsAction,
            'interpretation' => $this->interpretation,
        ]);
    }
}
