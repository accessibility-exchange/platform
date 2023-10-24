<?php

namespace App\Traits;

use App\Enums\UserContext;
use App\Models\User;

trait UserCanViewOwnedContent
{
    public function canViewOwnedContent(User $user): bool
    {
        if ($user->isAdministrator()) {
            return true;
        }

        if ($user->context === 'individual' && ! $user->oriented_at) {
            return false;
        }

        return
            $user->hasVerifiedEmail() &&
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
