<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * App\Models\Membership
 *
 * @property string $membershipable_type
 * @property int $membershipable_id
 */
class Membership extends MorphPivot
{
    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'memberships';

    /**
     * Return the parent user of the membership.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the parent model of the membership.
     */
    public function membershipable(): mixed
    {
        return $this->membershipable_type::where('id', $this->membershipable_id)->first();
    }
}
