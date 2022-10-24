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

    public function pending($query)
    {
        $query = $query->whereNull('oriented_at');

        return $query->whereNull('validated_at');
    }

    public function approved($query)
    {
        $query = $query->whereNotNull('oriented_at');

        return $query->whereNotNull('validated_at');
    }
}
