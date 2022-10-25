<?php

namespace App\Models\Scopes;

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EngagementProjectableNotSuspendedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->hasUser() && ! auth()->user()->isAdministrator()) {
            $builder->whereHas('project', function ($projectBuilder) {
                $projectBuilder->whereHasMorph(
                    'projectable',
                    [Organization::class, RegulatedOrganization::class],
                    function ($projectableBuilder) {
                        $projectableBuilder->whereNull('suspended_at');
                    }
                );
            });
        }
    }
}
