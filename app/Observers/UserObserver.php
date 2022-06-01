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
        if ($user->context === 'individual') {
            $user->individual()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'first_language' => $user->locale,
                'languages' => [$user->locale],
            ]);
        }
    }
}
