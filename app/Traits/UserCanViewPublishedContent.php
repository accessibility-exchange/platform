<?php

namespace App\Traits;

use App\Enums\UserContext;
use App\Models\User;

trait UserCanViewPublishedContent
{
    public function canViewPublishedContent(User $user): bool
    {
        if ($user->isAdministrator()) {
            return true;
        }

        $isOriented = $user->organization?->checkStatus('approved') || $user->regulatedOrganization?->checkStatus('approved') || $user->oriented_at != null;

        return
            $user->hasVerifiedEmail() &&
            $isOriented &&
            in_array(
                $user->context,
                [
                    UserContext::Individual->value,
                    UserContext::Organization->value,
                    UserContext::RegulatedOrganization->value,
                ]
            );
    }
}
