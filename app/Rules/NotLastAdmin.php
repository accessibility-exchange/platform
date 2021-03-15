<?php

namespace App\Rules;

use App\Models\Organization;
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
        return Organization::find($value->organization_id)->administrators()->count() > 1;
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
