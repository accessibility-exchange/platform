<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyIndividualRequest;
use App\Http\Requests\SaveIndividualRolesRequest;
use App\Http\Requests\UpdateIndividualCommunicationAndMeetingPreferencesRequest;
use App\Http\Requests\UpdateIndividualExperiencesRequest;
use App\Http\Requests\UpdateIndividualInterestsRequest;
use App\Http\Requests\UpdateIndividualRequest;
use App\Models\AccessSupport;
use App\Models\AgeGroup;
use App\Models\Community;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\IndividualRole;
use App\Models\LivedExperience;
use App\Models\Sector;
use App\Statuses\IndividualStatus;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndividualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('individuals.index', [
            'individuals' => Individual::status(new IndividualStatus('published'))->orderBy('name')->get(),
        ]);
    }

    /**
     * Show a role selection page for the logged-in user.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function showRoleSelection(): View
    {
        $this->authorize('selectRole', Auth::user());

        $individualRoles = IndividualRole::all();

        $roles = [];

        foreach ($individualRoles as $role) {
            $roles[$role->id] = [
                'label' => $role->name,
                'hint' => $role->description,
            ];
        }

        return view('individuals.show-role-selection', [
            'individual' => Auth::user()->individual,
            'roles' => $roles,
        ]);
    }

    /**
     * Show a role selection page for the logged-in user.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function showRoleEdit(): View
    {
        $this->authorize('selectRole', Auth::user());

        $individual = Auth::user()->individual;
        $individualRoles = IndividualRole::all();

        $roles = [];

        foreach ($individualRoles as $role) {
            $roles[$role->id] = [
                'label' => $role->name,
                'hint' => $role->description,
            ];
        }

        return view('individuals.show-role-edit', [
            'individual' => $individual,
            'roles' => $roles,
            'selectedRoles' => $individual->individualRoles->pluck('id')->toArray(),
        ]);
    }

    /**
     * Save roles for the logged-in user.
     *
     * @param SaveIndividualRolesRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function saveRoles(SaveIndividualRolesRequest $request): RedirectResponse
    {
        $this->authorize('selectRole', Auth::user());

        $data = $request->validated();

        $individual = Auth::user()->individual;

        $individual->individualRoles()->sync($data['roles'] ?? []);

        if (! $individual->fresh()->isConsultant() && ! $individual->fresh()->isConnector()) {
            $individual->unpublish();
        }

        return redirect(localized_route('dashboard'));
    }

    /**
     * Display the specified resource.
     *
     * @param Individual $individual
     * @return View
     */
    public function show(Individual $individual): View
    {
        return view('individuals.show', ['individual' => $individual]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Individual $individual
     * @return View
     */
    public function edit(Individual $individual): View
    {
        return view('individuals.edit', [
            'individual' => $individual,
            'regions' => get_regions(['CA'], locale()),
            'sectors' => Sector::all()->prepareForForm(),
            'impacts' => Impact::all()->prepareForForm(),
            'communities' => Community::pluck('name', 'id')->toArray(),
            'servicePreferences' => [
                'digital' => __('Digital services (websites, apps, etc.)'),
                'non-digital' => __('Non-digital services (phone lines, mail, in-person, etc.)'),
            ],
            'livedExperiences' => LivedExperience::pluck('name', 'id')->toArray(),
            'ageGroups' => AgeGroup::pluck('name', 'id')->toArray(),
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
            'languages' => ['' => __('Choose a language…')] + get_available_languages(true),
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
     * @param  UpdateIndividualRequest  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function update(UpdateIndividualRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        $individual->fill($data);

        $individual->save();

        if ($individual->isConnector()) {
            $individual->livedExperienceConnections()->sync($data['lived_experience_connections'] ?? []);
            if (isset($data['community_connections'])) {
                $individual->communityConnections()->sync($data['community_connections'] ?? []);
            }
            if (isset($data['age_group_connections'])) {
                $individual->ageGroupConnections()->sync($data['age_group_connections'] ?? []);
            }
        }

        return $individual->handleUpdateRequest($request, 1);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateIndividualExperiencesRequest  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function updateExperiences(UpdateIndividualExperiencesRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['relevant_experiences'])) {
            $relevant_experiences = array_filter(array_map('array_filter', $data['relevant_experiences']));
            if (empty($relevant_experiences)) {
                unset($data['relevant_experiences']);
            } else {
                $data['relevant_experiences'] = $relevant_experiences;
            }
        }

        $individual->fill($data);

        $individual->save();

        $individual->livedExperiences()->sync($data['lived_experiences'] ?? []);

        return $individual->handleUpdateRequest($request, 2);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateIndividualInterestsRequest  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function updateInterests(UpdateIndividualInterestsRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        $individual->fill($data);

        $individual->save();

        $individual->sectors()->sync($data['sectors'] ?? []);
        $individual->impacts()->sync($data['impacts'] ?? []);

        return $individual->handleUpdateRequest($request, 3);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateIndividualCommunicationAndMeetingPreferencesRequest  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function updateCommunicationAndMeetingPreferences(UpdateIndividualCommunicationAndMeetingPreferencesRequest $request, Individual $individual): RedirectResponse
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

        $individual->fill($data);

        $individual->save();

        return $individual->handleUpdateRequest($request, 4);
    }

    /**
     * Update the specified resource's status.
     *
     * @param  Request  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function updatePublicationStatus(Request $request, Individual $individual): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $individual->unpublish();
        } elseif ($request->input('publish')) {
            $individual->publish();
        }

        return redirect(localized_route('individuals.show', $individual));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyIndividualRequest  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function destroy(DestroyIndividualRequest $request, Individual $individual): RedirectResponse
    {
        $individual->delete();

        flash(__('Your individual page has been deleted.'), 'success');

        return redirect(localized_route('dashboard'));
    }

    /**
     * Express interest in a project.
     *
     * @param  Request  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function expressInterest(Request $request, Individual $individual): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
        ]);

        $individual->projectsOfInterest()->attach($request->input('project_id'));

        flash(__('You have expressed your interest in this project.'), 'success');

        return redirect()->back();
    }

    /**
     * Remove interest in a project.
     *
     * @param  Request  $request
     * @param Individual $individual
     * @return RedirectResponse
     */
    public function removeInterest(Request $request, Individual $individual): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
        ]);

        $individual->projectsOfInterest()->detach($request->input('project_id'));

        flash(__('You have removed your expression of interest in this project.'), 'success');

        return redirect()->back();
    }
}
