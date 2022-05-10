<?php

namespace App\Statuses;

class RegulatedOrganizationStatus extends \Makeable\EloquentStatus\Status
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
