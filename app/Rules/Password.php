<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Password extends \Laravel\Fortify\Rules\Password implements Rule
{
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->message) {
            return $this->message;
        }

        switch (true) {
            case $this->requireUppercase
                && ! $this->requireNumeric
                && ! $this->requireSpecialCharacter:
                return __('validation.custom.password.length-uppercase', [
                    'length' => $this->length,
                ]);

            case $this->requireNumeric
                && ! $this->requireUppercase
                && ! $this->requireSpecialCharacter:
                return __('validation.custom.password.length-numeric', [
                    'length' => $this->length,
                ]);

            case $this->requireSpecialCharacter
                && ! $this->requireUppercase
                && ! $this->requireNumeric:
                return __('validation.custom.password.length-specialcharacter', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
                && $this->requireNumeric
                && ! $this->requireSpecialCharacter:
                return __('validation.custom.password.length-uppercase-numeric', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
                && $this->requireSpecialCharacter
                && ! $this->requireNumeric:
                return __('validation.custom.password.length-uppercase-specialcharacter', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
                && $this->requireNumeric
                && $this->requireSpecialCharacter:
                return __('validation.custom.password.length-uppercase-numeric-specialcharacter', [
                    'length' => $this->length,
                ]);

            case $this->requireNumeric
                && $this->requireSpecialCharacter
                && ! $this->requireUppercase:
                return __('validation.custom.password.length-numeric-specialcharacter', [
                    'length' => $this->length,
                ]);

            default:
                return __('validation.custom.password.length', [
                    'length' => $this->length,
                ]);
        }
    }
}
