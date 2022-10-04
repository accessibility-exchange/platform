<?php

namespace App\Policies;

use App\Models\Engagement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EngagementPolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function view(User $user, Engagement $engagement): Response
    {
        return
            $user->individual || $user->organization || $user->regulated_organization
            && $engagement->checkStatus('published') || $user->can('update', $engagement)
                ? Response::allow()
                : Response::denyAsNotFound();
    }

    public function update(User $user, Engagement $engagement): Response
    {
        return $user->can('update', $engagement->project)
            ? Response::allow()
            : Response::deny();
    }

    public function addConnector(User $user, Engagement $engagement): Response
    {
        return
            $user->can('update', $engagement)
            && ! $engagement->connector
            && ! $engagement->organizationalConnector
            && ! $engagement->invitations->where('role', 'connector')->count()
                ? Response::allow()
                : Response::deny();
    }

    public function viewParticipants(User $user, Engagement $engagement): Response
    {
        if ($user->isAdministratorOf($engagement->project->projectable)) {
            return Response::allow();
        }

        if ($engagement->organizationalConnector && $user->isAdministratorOf($engagement->organizationalConnector)) {
            return Response::allow();
        }

        if ($engagement->connector?->id === $user->individual?->id) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function manageParticipants(User $user, Engagement $engagement): Response
    {
        if ($engagement->organizationalConnector && $user->isAdministratorOf($engagement->organizationalConnector)) {
            return Response::allow();
        }

        if ($engagement->connector?->id === $user->individual?->id) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function participate(User $user, Engagement $engagement): Response
    {
        return $engagement->confirmedParticipants->contains($user->individual)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
