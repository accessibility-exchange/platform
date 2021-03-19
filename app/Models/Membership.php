<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends MorphPivot
{
    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'memberships';

    /**
     * Get the user who has this membership.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the belonged-to model.
     */
    public function memberable()
    {
        return $this->membership_type::where('id', $this->membership_id)->first();
    }
}
