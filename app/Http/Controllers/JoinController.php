<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveJoinRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class JoinController extends Controller
{
    /**
     * Cancel a request to join a team.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancel(Request $request): RedirectResponse
    {
        $joinable = $request->user()->joinable;

        $request->user()->forceFill([
            'joinable_type' => null,
            'joinable_id' => null,
        ])->save();

        flash(__('You have cancelled your request to join :team.', ['team' => $joinable->name]), 'success');

        return back();
    }

    /**
     * Approve a request to join a team.
     *
     * @param ApproveJoinRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function approve(ApproveJoinRequest $request, User $user): RedirectResponse
    {
        $joinable = $user->joinable;

        $data = $request->validated();

        $user->forceFill([
            'joinable_type' => null,
            'joinable_id' => null,
        ])->save();

        $joinable->users()->attach($user, ['role' => $data['role']]);

        flash(__('You have approved :name’s request to join :team and granted them :role privileges.', ['name' => $user->name, 'team' => $joinable->name, 'role' => __('roles.' . $data['role'])]), 'success');

        return back();
    }

    /**
     * Deny a request to join a team.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function deny(Request $request, User $user): RedirectResponse
    {
        $joinable = $user->joinable;

        Gate::forUser($request->user())->authorize('update', $joinable);

        $user->forceFill([
            'joinable_type' => null,
            'joinable_id' => null,
        ])->save();

        flash(__('You have denied :name’s request to join :team.', ['name' => $user->name, 'team' => $joinable->name]), 'success');

        return back();
    }
}
