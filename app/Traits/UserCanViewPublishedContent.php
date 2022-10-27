<?php

namespace App\Traits;

use App\Enums\UserContext;
use App\Models\User;

trait UserCanViewPublishedContent
{
    public function canViewPublishedContent(User $user): bool
    {
        return
            $user->hasVerifiedEmail() &&
            in_array(
                $user->context,
                [
                    UserContext::Administrator->value,
                    UserContext::Individual->value,
                    UserContext::Organization->value,
                    UserContext::RegulatedOrganization->value,
                ]
            );
    }
}
