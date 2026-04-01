<?php

namespace App\Observers;

use App\Enums\UserContext;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\NewOrganizationRegistered;
use Illuminate\Support\Facades\Notification;

class OrganizationObserver
{
    public function created(Organization $organization): void
    {
        $creator = User::whereBlind('email', 'email_index', $organization->contact_person_email)->first();

        if ($creator) {
            $admins = User::whereAdministrator()->get();
            Notification::send($admins, new NewOrganizationRegistered(
                creatorName: $creator->name,
                organization: $organization,
                userContext: UserContext::from($creator->context),
            ));
        }
    }
}
