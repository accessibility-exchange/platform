<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrganizationNotSuspendedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->hasUser() && ! auth()->user()->isAdministrator()) {
            $builder
                ->whereHas('users', function (Builder $userQuery) {
                    $userQuery->where('user_id', auth()->user()->id);
                })
                ->orWhereNull('suspended_at');
        }
    }
}
