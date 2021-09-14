<?php

namespace App\Statuses;

use Carbon\Carbon;
use Makeable\EloquentStatus\Status;

class ProjectStatus extends status
{
    public function draft($query)
    {
        return $query->whereNull('published_at');
    }

    public function published($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function started($query)
    {
        return $query->whereDate('start_date', '<', Carbon::now());
    }

    public function active($query)
    {
        $now = Carbon::now();

        return $query
            ->whereDate('start_date', '<', $now)
            ->where(function ($query) {
                $query->where('end_date', '<', $now)
                      ->orWhereNull('end_date');
            });
    }

    public function prepared($query)
    {
        return $query->whereNotNull('prepared_at');
    }

    public function matched($query)
    {
        return $query->whereNotNull('matched');
    }

    public function negotiated($query)
    {
        return $query->whereNotNull('negotiated');
    }

    public function consulted($query)
    {
        return $query->whereNotNull('consulted');
    }

    public function completed($query)
    {
        return $query->whereDate('end_date', '<', Carbon::now());
    }
}
