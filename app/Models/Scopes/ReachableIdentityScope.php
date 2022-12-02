<?php

namespace App\Models\Scopes;

use App\Enums\IdentityCluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ReachableIdentityScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereJsonDoesntContain('clusters', IdentityCluster::Unreachable);
    }
}
