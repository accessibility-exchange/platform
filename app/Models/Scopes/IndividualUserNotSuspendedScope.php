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
                    ->where('id', auth()->user()->id)
                    ->orWhereNull('suspended_at');
            });
        }
    }
}
