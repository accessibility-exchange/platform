<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommunityMemberRequest;
use App\Http\Requests\DestroyCommunityMemberRequest;
use App\Http\Requests\UpdateCommunityMemberAccessAndAccomodationsRequest;
use App\Http\Requests\UpdateCommunityMemberCommunicationPreferencesRequest;
use App\Http\Requests\UpdateCommunityMemberExperiencesRequest;
use App\Http\Requests\UpdateCommunityMemberInterestsRequest;
use App\Http\Requests\UpdateCommunityMemberRequest;
use App\Models\CommunityMember;
use App\Models\Impact;
use App\Models\LivedExperience;
use App\Models\Sector;
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
        $data = $request->validated();

        $communityMember = CommunityMember::create($data);

        flash(__('Your draft community member page has been saved.'), 'success');

        return redirect(\localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));
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
            'sectors' => Sector::all()->pluck('name', 'id')->toArray(),
            'impacts' => Impact::all()->pluck('name', 'id')->toArray(),
            'livedExperiences' => LivedExperience::all()->pluck('name', 'id')->toArray(),
            'ageGroups' => [
                'youth' => __('Youth (18–24)'),
                'adult' => __('Adult (25–64)'),
                'senior' => __('Senior (65+)'),
            ],
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
        $data = $request->validated();

        if (isset($data['other_links'])) {
            $data['other_links'] = array_filter(array_map('array_filter', $data['other_links']));
        }

        $communityMember->fill($data);

        $communityMember->save();

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

        if ($request->input('save_and_next')) {
            $step = 2;
        }

        return redirect(\localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => $step ?? 1]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommunityMemberInterestsRequest  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateInterests(UpdateCommunityMemberInterestsRequest $request, CommunityMember $communityMember): RedirectResponse
    {
        $data = $request->validated();

        $communityMember->fill($data);

        $communityMember->save();

        $communityMember->sectors()->sync($data['sectors'] ?? []);
        $communityMember->impacts()->sync($data['impacts'] ?? []);

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

        return redirect(\localized_route('community-members.show', $communityMember));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommunityMemberExperiencesRequest  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateExperiences(UpdateCommunityMemberExperiencesRequest $request, CommunityMember $communityMember): RedirectResponse
    {
        $data = $request->validated();

        $communityMember->fill($data);

        $communityMember->save();

        $communityMember->livedExperiences()->sync($data['lived_experiences'] ?? []);

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

        return redirect(\localized_route('community-members.show', $communityMember));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommunityMemberCommunicationPreferencesRequest  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCommunicationPreferences(UpdateCommunityMemberCommunicationPreferencesRequest $request, CommunityMember $communityMember): RedirectResponse
    {
        $data = $request->validated();

        $communityMember->fill($data);

        $communityMember->save();

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

        return redirect(\localized_route('community-members.show', $communityMember));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommunityMemberAccessAndAccomodationsRequest  $request
     * @param  \App\Models\CommunityMember  $communityMember
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAccessAndAccomodations(UpdateCommunityMemberAccessAndAccomodationsRequest $request, CommunityMember $communityMember): RedirectResponse
    {
        $data = $request->validated();

        $communityMember->fill($data);

        $communityMember->save();

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

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
