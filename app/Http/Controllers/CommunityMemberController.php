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
        $languages = (new LanguageRepository)->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key] = $language->getName();
        }

        return view('community-members.edit', [
            'communityMember' => $communityMember,
            'regions' => get_regions(['CA'], \locale()),
            'sectors' => Sector::all()->pluck('name', 'id')->toArray(),
            'impacts' => Impact::all()->pluck('name', 'id')->toArray(),
            'servicePreferences' => [
                'digital' => __('Digital services (websites, apps, etc.)'),
                'non-digital' => __('Non-digital services (phone lines, mail, in-person, etc.)'),
            ],
            'livedExperiences' => LivedExperience::all()->pluck('name', 'id')->toArray(),
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

        if (! isset($data['hide_location'])) {
            $data['hide_location'] = false;
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

        if (! isset($data['service_preference'])) {
            $data['service_preference'] = [];
        }

        $communityMember->fill($data);

        $communityMember->save();

        $communityMember->sectors()->sync($data['sectors'] ?? []);
        $communityMember->impacts()->sync($data['impacts'] ?? []);

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

        if ($request->input('save_and_next')) {
            $step = 3;
        } elseif ($request->input('save_and_previous')) {
            $step = 1;
        }

        return redirect(\localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => $step ?? 2]));
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

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

        if ($request->input('save_and_next')) {
            $step = 4;
        } elseif ($request->input('save_and_previous')) {
            $step = 2;
        }

        return redirect(\localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => $step ?? 3]));
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

        if (isset($data['languages'])) {
            $languages = array_filter($data['languages']);
            if (empty($languages)) {
                unset($data['languages']);
            } else {
                $data['languages'] = $languages;
            }
        }

        $communityMember->fill($data);

        $communityMember->save();

        if ($communityMember->checkStatus('draft')) {
            flash(__('Your draft community member page has been updated.'), 'success');
        } else {
            flash(__('Your community member page has been updated.'), 'success');
        }

        if ($request->input('save_and_next')) {
            $step = 5;
        } elseif ($request->input('save_and_previous')) {
            $step = 3;
        }

        return redirect(\localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => $step ?? 4]));
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

        if ($request->input('save_and_previous')) {
            $step = 4;
        }

        return redirect(\localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => $step ?? 5]));
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
