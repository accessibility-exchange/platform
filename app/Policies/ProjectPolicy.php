<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function viewAny(User $user): Response
    {
        return $user->individual || $user->organization || $user->regulated_organization
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, Project $project): Response
    {
        return
            $user->individual || $user->organization || $user->regulated_organization
            && $project->checkStatus('published') || $user->can('update', $project)
                ? Response::allow()
                : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ! is_null($user->projectable()) && $user->isAdministratorOf($user->projectable());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $user->isAdministratorOf($project->projectable);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function manage(User $user, Project $project)
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

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return $user->isAdministratorOf($project->projectable);
    }

    /**
     * Determine whether the user can create an engagement for an project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function createEngagement(User $user, Project $project)
    {
        return $user->isAdministratorOf($project->projectable)
            ? Response::allow()
            : Response::deny('You cannot create an engagement for this project.');
    }
}
