<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNotificationableRequest;
use App\Http\Requests\RemoveNotificationableRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class NotificationListController extends Controller
{
    public function show(): View
    {
        return view('settings.notification-list');
    }

    public function add(AddNotificationableRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $notificationable = $data['notificationable_type']::find($data['notificationable_id']);

        if ($notificationable->isNotifying(request()->user())) {
            flash(__(':notificationable is already on your notification list.', ['notificationable' => $notificationable->name]), 'warning');

            return redirect()->back();
        }

        if ($data['notificationable_type'] === 'App\Models\Organization') {
            request()->user()->organizationsForNotification()->attach($notificationable);
        }

        if ($data['notificationable_type'] === 'App\Models\RegulatedOrganization') {
            request()->user()->regulatedOrganizationsForNotification()->attach($notificationable);
        }

        flash(__('You have successfully added :notificationable to your list.', ['notificationable' => $notificationable->name]), 'success');

        return redirect()->back();
    }

    public function remove(RemoveNotificationableRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $notificationable = $data['notificationable_type']::find($data['notificationable_id']);

        if (! $notificationable->isNotifying(request()->user())) {
            flash(__(':notificationable could not be removed because it was not on your notification list.', ['notificationable' => $notificationable->name]), 'warning');

            return redirect()->back();
        }

        if ($data['notificationable_type'] === 'App\Models\Organization') {
            request()->user()->organizationsForNotification()->detach($notificationable);
        }

        if ($data['notificationable_type'] === 'App\Models\RegulatedOrganization') {
            request()->user()->regulatedOrganizationsForNotification()->detach($notificationable);
        }

        flash(__('You have successfully removed :notificationable from your notification list.', ['notificationable' => $notificationable->name]), 'success');

        return redirect()->back();
    }
}
