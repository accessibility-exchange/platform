<?php

namespace App\Observers;

use App\Enums\UserContext;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\NewRegulatedOrganizationRegistered;
use Illuminate\Support\Facades\Notification;

class RegulatedOrganizationObserver
{
    public function created(RegulatedOrganization $regulatedOrganization): void
    {
        $creator = User::whereBlind('email', 'email_index', $regulatedOrganization->contact_person_email)->first();

        if ($creator) {
            $admins = User::whereAdministrator()->get();
            Notification::send($admins, new NewRegulatedOrganizationRegistered(
                creatorName: $creator->name,
                regulatedOrganization: $regulatedOrganization,
                userContext: UserContext::from($creator->context),
            ));
        }
    }
}
