<?php

namespace App\Observers;

use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\NewOrganizationRegistered;
use Illuminate\Support\Facades\Notification;

class RegulatedOrganizationObserver
{
    public function created(RegulatedOrganization $regulatedOrganization): void
    {
        $creator = User::whereBlind('email', 'email_index', $regulatedOrganization->contact_person_email)->first();

        if ($creator) {
            $admins = User::whereAdministrator()->get();

            Notification::send($admins, new NewOrganizationRegistered(
                creatorName: $creator->name,
                organizationName: $regulatedOrganization->getTranslation('name', 'en'),
                creatorEmail: $regulatedOrganization->contact_person_email,
                userContext: $creator->context,
            ));
        }
    }
}
