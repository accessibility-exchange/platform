<?php

namespace App\Statuses;

use Makeable\EloquentStatus\Status;

class EngagementStatus extends Status
{
    public function draft($query)
    {
        return $query->whereNull('published_at');
    }

    public function published($query)
    {
        return $query->whereNotNull('published_at');
    }
}
