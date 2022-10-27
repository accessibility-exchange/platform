<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class IndividualUserNotSuspendedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->hasUser() && ! auth()->user()->isAdministrator()) {
            $builder->whereHas('user', function ($userBuilder) {
                $userBuilder
                    // Individual should be shown/included in query results if it belongs to the current user
                    ->where('id', auth()->user()->id)
                    // Individual should be shown/included in query results if it belongs to a user who is not suspended
                    ->orWhereNull('suspended_at');
            });
        }
    }
}
