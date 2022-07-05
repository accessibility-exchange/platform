<?php

namespace App\Rules;

use Hearth\Models\Membership;
use Illuminate\Contracts\Validation\Rule;

class NotLastAdmin implements Rule
{
    /**
     * The membership under validation.
     *
     * @var Membership
     */
    private Membership $membership;

    /**
     * Constructor.
     *
     * @param  Membership  $membership
     */
    public function __construct(Membership $membership)
    {
        $this->membership = $membership;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->membership->membershipable()->administrators->count() > 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.custom.membership.not_last_admin');
    }
}
