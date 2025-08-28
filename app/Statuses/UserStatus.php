<?php

namespace App\Statuses;

use Makeable\EloquentStatus\Status;

class UserStatus extends Status
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
