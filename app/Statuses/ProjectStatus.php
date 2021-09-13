<?php

namespace App\Statuses;

use Carbon\Carbon;
use Makeable\EloquentStatus\Status;

class ProjectStatus extends status
{
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

    public function completed($query)
    {
        return $query->whereDate('end_date', '<', Carbon::now());
    }
}
