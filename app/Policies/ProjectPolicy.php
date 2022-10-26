<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): null|Response
    {
        if ($user->isAdministrator() && $ability === 'viewAny') {
            return Response::allow();
        }

        if ($user->isSuspended() && $ability !== 'view') {
            return Response::deny(__('This page is not available because your account has been suspended.'));
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        return $user->individual || $user->organization || $user->regulated_organization
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, Project $project): Response
    {
        if ($user->isSuspended() && $user->isAdministratorOf($project->projectable) && $project->isPublishable()) {
            return Response::allow();
        }

        if ($user->isAdministrator() && $project->isPublishable()) {
            return Response::allow();
        }

        return
            $user->individual || $user->organization || $user->regulated_organization
            && $project->checkStatus('published') || ($user->can('update', $project) && $project->isPublishable())
                ? Response::allow()
                : Response::denyAsNotFound();
    }

    public function create(User $user): bool
    {
        return ! is_null($user->projectable()) && $user->isAdministratorOf($user->projectable());
    }

    public function update(User $user, Project $project): bool
    {
        return $user->isAdministratorOf($project->projectable);
    }

    public function manage(User $user, Project $project): bool
    {
        return $user->isAdministratorOf($project->projectable);
    }

    public function publish(User $user, Project $project): Response
    {
        return $user->isAdministratorOf($project->projectable) && $project->isPublishable()
            ? Response::allow()
            : Response::deny(__('You cannot publish this organization.'));
    }

    public function unpublish(User $user, Project $project): Response
    {
        return $user->isAdministratorOf($project->projectable)
            ? Response::allow()
            : Response::deny(__('You cannot unpublish this organization.'));
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isAdministratorOf($project->projectable);
    }

    public function createEngagement(User $user, Project $project): Response
    {
        return $user->isAdministratorOf($project->projectable)
            ? Response::allow()
            : Response::deny(__('You cannot create an engagement for this project.'));
    }
}
