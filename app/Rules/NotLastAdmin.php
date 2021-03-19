<?php

namespace App\Rules;

use App\Models\Membership;
use Illuminate\Contracts\Validation\Rule;

class NotLastAdmin implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value->memberable()->administrators()->count() > 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.organization_user.not_last_admin');
    }
}
