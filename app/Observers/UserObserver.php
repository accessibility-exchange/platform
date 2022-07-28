<?php

namespace App\Observers;

use App\Models\User;
use ParagonIE\CipherSweet\CipherSweet as CipherSweetEngine;
use ParagonIE\CipherSweet\EncryptedField;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->context === 'individual') {
            $user->individual()->create([
                'user_id' => $user->id,
                'name' => (new EncryptedField(
                    app(CipherSweetEngine::class),
                    'users',
                    'name'
                ))->decryptValue($user->name),
                'first_language' => $user->locale,
                'languages' => [$user->locale],
            ]);
        }
    }
}
