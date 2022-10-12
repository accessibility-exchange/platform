<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Str;

class UniqueUserEmail implements InvokableRule
{
    public function __invoke($attribute, mixed $value, $fail): void
    {
        if (User::whereBlind($attribute, $attribute.'_index', Str::lower($value))->exists()) {
            $fail(__('A user with this email already exists.'));
        }
    }
}
