<?php

namespace App\Livewire;

use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use RalphJSmit\Livewire\Urls\Facades\Url;

class MarkNotificationAsRead extends Component
{
    public mixed $notification;

    public function render()
    {
        return view('livewire.mark-notification-as-read');
    }

    public function markAsRead(): RedirectResponse|Redirector
    {
        $this->notification->markAsRead();

        flash(__('The notification has been marked as read.'), 'success');

        return redirect()->to(Url::current());
    }
}
