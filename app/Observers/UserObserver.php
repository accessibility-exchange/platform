<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if ($user->context === 'community-member') {
            $user->communityMember()->create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
        }
    }
}
