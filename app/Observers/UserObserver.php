<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->context === 'individual') {
            $user->individual()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'first_language' => $user->locale,
                'languages' => [$user->locale],
                'email' => $user->email,
                'preferred_contact_person' => 'me',
                'preferred_contact_method' => 'email',
                'preferred_notification_method' => 'email',
                'notifications' => [
                    'reports' => [
                        'channels' => [
                            'website',
                        ],
                    ],
                    'projects' => [
                        'channels' => [
                            'website',
                        ],
                        'creators' => [],
                        'types' => [],
                        'engagements' => [],
                    ],
                    'updates' => [
                        'channels' => [
                            'website',
                        ],
                    ],
                ],
            ]);
        }
    }
}
