<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;

trait RetrievesUserByNormalizedEmail
{
    public function retrieveUserByEmail(string $email): ?User
    {
        return User::whereBlind('email', 'email_index', Str::lower($email))->first();
    }
}
