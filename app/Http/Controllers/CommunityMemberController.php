<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCommunityMemberRequest;
use App\Http\Requests\StoreCommunityMemberRequest;
use App\Http\Requests\UpdateCommunityMemberAccessAndAccomodationsRequest;
use App\Http\Requests\UpdateCommunityMemberCommunicationPreferencesRequest;
use App\Http\Requests\UpdateCommunityMemberExperiencesRequest;
use App\Http\Requests\UpdateCommunityMemberInterestsRequest;
use App\Http\Requests\UpdateCommunityMemberRequest;
use App\Models\AccessSupport;
use App\Models\CommunityMember;
use App\Models\Impact;
use App\Models\LivedExperience;
use App\Models\Sector;
use App\Statuses\CommunityMemberStatus;
use CommerceGuys\Intl\Language\LanguageRepository;
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommunityMemberRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCommunityMemberRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $communityMember = CommunityMember::create($data);

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
        $languages = (new LanguageRepository)->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key] = $language->getName();
        }

        return view('community-members.edit', [
            'communityMember' => $communityMember,
            'regions' => get_regions(['CA'], \locale()),
            'sectors' => Sector::pluck('name', 'id')->toArray(),
            'impacts' => Impact::pluck('name', 'id')->toArray(),
            'servicePreferences' => [
                'digital' => __('Digital services (websites, apps, etc.)'),
                'non-digital' => __('Non-digital services (phone lines, mail, in-person, etc.)'),
            ],
            'livedExperiences' => LivedExperience::pluck('name', 'id')->toArray(),
            'ageGroups' => [
                'youth' => __('Youth (18–24)'),
                'adult' => __('Adult (25–64)'),
                'senior' => __('Senior (65+)'),
            ],
            'livingSituations' => [
                'urban' => __('Urban'),
                'suburban' => __('Suburban'),
                'rural' => __('Rural'),
            ],
            'creators' => [
                'self' => __('I’m creating it myself'),
                'other' => __('Someone else is creating it for me'),
            ],
            'contactMethods' => [
                'email' => __('Email'),
                'text' => __('Text message'),
                'phone' => __('Phone call'),
                'vrs' => __('Phone call with Video Relay Service (VRS)'),
                'support_person' => __('Contact my support person'),
            ],
            'languages' => ['' => __('Choose a language…')] + $languages,
            'meetingTypes' => [
                'in_person' => __('In person'),
                'web_conference' => __('Virtual – web conference'),
                'phone' => __('Virtual – phone call'),
            ],
            'accessNeeds' => AccessSupport::pluck('name', 'id')->toArray(),
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
            $other_links = array_filter(array_map('array_filter', $data['other_links']));
            if (empty($other_links)) {
                unset($data['other_links']);
            } else {
                $data['other_links'] = $other_links;
            }
        }

        $communityMember->fill($data);

        $communityMember->save();

        return $communityMember->handleUpdateRequest($request, 1);
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

        if (! isset($data['service_preference'])) {
            $data['service_preference'] = [];
        }

        $communityMember->fill($data);

        $communityMember->save();

        $communityMember->sectors()->sync($data['sectors'] ?? []);
        $communityMember->impacts()->sync($data['impacts'] ?? []);

        return $communityMember->handleUpdateRequest($request, 2);
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

        if (! isset($data['rural_or_remote'])) {
            $data['rural_or_remote'] = false;
        }

        if (isset($data['work_and_volunteer_experiences'])) {
            $work_and_volunteer_experiences = array_filter(array_map('array_filter', $data['work_and_volunteer_experiences']));
            if (empty($work_and_volunteer_experiences)) {
                unset($data['work_and_volunteer_experiences']);
            } else {
                $data['work_and_volunteer_experiences'] = $work_and_volunteer_experiences;
            }
        }

        $communityMember->fill($data);

        $communityMember->save();

        $communityMember->livedExperiences()->sync($data['lived_experiences'] ?? []);

        return $communityMember->handleUpdateRequest($request, 3);
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

        if (isset($data['support_people'])) {
            $support_people = array_filter(array_map('array_filter', $data['support_people']));
            if (empty($support_people)) {
                unset($data['support_people']);
            } else {
                $data['support_people'] = $support_people;
            }
        }

        $data['languages'] = array_filter($data['languages']);

        $communityMember->fill($data);

        $communityMember->save();

        return $communityMember->handleUpdateRequest($request, 4);
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

        $communityMember->accessSupports()->sync($data['access_needs'] ?? []);

        return $communityMember->handleUpdateRequest($request, 5);
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
            $communityMember->unpublish();
        } elseif ($request->input('publish')) {
            $communityMember->publish();
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
