<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockRequest;
use App\Http\Requests\UnblockRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BlockListController extends Controller
{
    public function show(): View
    {
        return view('users.settings.block-list');
    }

    public function block(BlockRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $blockable = $data['blockable_type']::find($data['blockable_id']);

        if ($blockable->blockedBy(request()->user())) {
            flash(__(':blockable is already on your block list.', ['blockable' => $blockable->name]), 'warning');

            return redirect(localized_route('dashboard'));
        }

        if ($data['blockable_type'] === 'App\Models\Organization') {
            request()->user()->blockedOrganizations()->attach($blockable);
        }

        if ($data['blockable_type'] === 'App\Models\RegulatedOrganization') {
            request()->user()->blockedRegulatedOrganizations()->attach($blockable);
        }

        if ($data['blockable_type'] === 'App\Models\Individual') {
            request()->user()->blockedIndividuals()->attach($blockable);
        }

        flash(__('You have successfully blocked :blockable.', ['blockable' => $blockable->name]), 'success');

        return redirect(localized_route('dashboard'));
    }

    public function unblock(UnblockRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $blockable = $data['blockable_type']::find($data['blockable_id']);

        if (! $blockable->blockedBy(request()->user())) {
            flash(__(':blockable could not be unblocked because it was not on your block list.', ['blockable' => $blockable->name]), 'warning');

            return redirect(localized_route('dashboard'));
        }

        if ($data['blockable_type'] === 'App\Models\Organization') {
            request()->user()->blockedOrganizations()->detach($blockable);
        }

        if ($data['blockable_type'] === 'App\Models\RegulatedOrganization') {
            request()->user()->blockedRegulatedOrganizations()->detach($blockable);
        }

        if ($data['blockable_type'] === 'App\Models\Individual') {
            request()->user()->blockedIndividuals()->detach($blockable);
        }

        flash(__('You have successfully unblocked :blockable.', ['blockable' => $blockable->name]), 'success');

        return redirect(localized_route('block-list.show'));
    }
}
