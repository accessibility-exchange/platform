<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommunityMemberRequest;
use App\Http\Requests\DestroyCommunityMemberRequest;
use App\Http\Requests\UpdateCommunityMemberRequest;
use App\Models\CommunityMember;
use App\Statuses\CommunityMemberStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('community-members.index', [
            'communityMembers' => CommunityMember::status(new CommunityMemberStatus('published'))->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create', CommunityMember::class);

        return view('community-members.create', [
            'regions' => get_regions(['CA'], \locale()),
            'creators' => [
                'self' => __('I’m creating it myself'),
                'other' => __('Someone else is creating it for me'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateCommunityMemberRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCommunityMemberRequest $request): RedirectResponse
    {
        $communityMember = CommunityMember::create($request->safe()->except('picture'));

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $communityMember->addMediaFromRequest('picture')->toMediaCollection('picture');
        }

        flash(__('Your draft community member page has been saved.'), 'success');

        return redirect(\localized_route('community-members.show', ['communityMember' => $communityMember]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\View\View
     */
    public function show(CommunityMember $communityMember): View
    {
        if ($communityMember->checkStatus('draft')) {
            return view('community-members.show-draft', ['communityMember' => $communityMember]);
        }

        return view('community-members.show', ['communityMember' => $communityMember]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\View\View
     */
    public function edit(CommunityMember $communityMember): View
    {
        return view('community-members.edit', [
            'communityMember' => $communityMember,
            'regions' => get_regions(['CA'], \locale()),
            'creators' => [
                'self' => __('I’m creating it myself'),
                'other' => __('Someone else is creating it for me'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommunityMemberRequest  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCommunityMemberRequest $request, CommunityMember $communityMember): RedirectResponse
    {
        $communityMember->fill($request->safe()->except('picture'));

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $communityMember->addMediaFromRequest('picture')->toMediaCollection('picture');
        } elseif (! $request->hasFile('picture')) {
            $communityMember->clearMediaCollection('picture');
        }

        $communityMember->save();

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        }

        flash(__('Your community member page has been updated.'), 'success');

        return redirect(\localized_route('community-members.show', $communityMember));
    }

    /**
     * Update the specified resource's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePublicationStatus(Request $request, CommunityMember $communityMember): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $communityMember->published_at = null;
            $communityMember->save();

            flash(__('Your community member page has been unpublished.'), 'success');
        } elseif ($request->input('publish')) {
            $communityMember->published_at = date('Y-m-d h:i:s', time());
            $communityMember->save();

            flash(__('Your community member page has been published.'), 'success');
        }

        return redirect(\localized_route('community-members.show', $communityMember));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyCommunityMemberRequest  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyCommunityMemberRequest $request, CommunityMember $communityMember): RedirectResponse
    {
        $communityMember->delete();

        flash(__('Your community member page has been deleted.'), 'success');

        return redirect(\localized_route('dashboard'));
    }

    /**
     * Express interest in a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function expressInterest(Request $request, CommunityMember $communityMember): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
        ]);

        $communityMember->projectsOfInterest()->attach($request->input('project_id'));

        flash(__('You have expressed your interest in this project.'), 'success');

        return redirect()->back();
    }

    /**
     * Remove interest in a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeInterest(Request $request, CommunityMember $communityMember): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
        ]);

        $communityMember->projectsOfInterest()->detach($request->input('project_id'));

        flash(__('You have removed your expression of interest in this project.'), 'success');

        return redirect()->back();
    }
}
