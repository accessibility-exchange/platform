<?php

namespace App\Actions\Fortify;

use App\Rules\Password as PasswordRule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', Password::defaults(), new PasswordRule(), 'confirmed'];
    }
}
