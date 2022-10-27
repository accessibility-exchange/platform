<?php

namespace App\Statuses;

class UserStatus extends \Makeable\EloquentStatus\Status
{
    public function pending($query)
    {
        return $query->whereNull('oriented_at');
    }

    public function approved($query)
    {
        return $query->whereNotNull('oriented_at');
    }

    public function suspended($query)
    {
        return $query->whereNotNull('suspended_at');
    }
}
