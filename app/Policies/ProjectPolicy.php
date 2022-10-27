<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Traits\UserCanViewPublishedContent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class ProjectPolicy
{
    use HandlesAuthorization;
    use UserCanViewPublishedContent;

    public function before(User $user, string $ability): null|Response
    {
        if ($user->checkStatus('suspended') && $ability !== 'view') {
            return Response::deny(Str::markdown(
                __('Your account has been suspended. Because of that, you do not have access to this page. Please contact us if you need further assistance.')
                .contact_information()
            ));
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canViewPublishedContent($user);
    }

    public function view(User $user, Project $project): Response
    {
        // User can't view project by organization or regulated organization which they have blocked.
        if ($project->projectable->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :organization. If you want to visit this page, you can :unblock and return to this page.', [
                'organization' => '<strong>'.$project->projectable->getTranslation('name', locale()).'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        // Previewable drafts can be viewed by their team members or platform administrators.
        if ($project->checkStatus('draft')) {
            if ($project->isPreviewable()) {
                return $user->isMemberOf($project->projectable) || $user->isAdministrator()
                    ? Response::allow()
                    : Response::denyAsNotFound();
            }

            return Response::denyAsNotFound();
        }

        // Suspended users can view or preview their own projects.
        if ($user->checkStatus('suspended') && $project->isPreviewable()) {
            return $user->isMemberOf($project->projectable)
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        // Catch-all rule for published project pages.
        return $this->canViewPublishedContent($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user): bool
    {
        return $user->projectable && $user->isAdministratorOf($user->projectable);
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
