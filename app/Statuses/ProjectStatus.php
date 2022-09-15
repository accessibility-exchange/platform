<?php

namespace App\Statuses;

class ProjectStatus extends \Makeable\EloquentStatus\Status
{
    public function draft($query)
    {
        return $query->whereNull('published_at');
    }

    public function published($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function estimateRequested($query)
    {
        return $query->whereNotNull('estimate_requested_at');
    }

    public function estimateApproved($query)
    {
        return $query->whereNotNull('estimate_approved_at');
    }

    public function agreementReceived($query)
    {
        return $query->whereNotNull('agreement_received_at');
    }
}
