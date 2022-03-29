<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProjectRequest;
use App\Http\Requests\StoreProjectContextRequest;
use App\Http\Requests\StoreProjectFocusRequest;
use App\Http\Requests\StoreProjectLanguagesRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\CommunityMember;
use App\Models\Entity;
use App\Models\Project;
use App\Statuses\ProjectStatus;
use CommerceGuys\Intl\Language\LanguageRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Project::status(new ProjectStatus('published'))
                ->with('entity')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\View\View
     */
    public function create(Entity $entity): View
    {
        $languages = (new LanguageRepository)->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key] = $language->getName();
        }

        $languages = $languages + [
            'ase' => __('American Sign Language'),
            'fcs' => __('Quebec Sign Language'),
        ];

        return view('projects.create', [
            'entity' => $entity,
            'languages' => [
                '' => __('Choose a language…'),

            ] + Arr::sort($languages),
        ]);
    }

    /**
     * Store a new project's context in the session.
     *
     * @param  \App\Http\Requests\StoreProjectContextRequest  $request
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeContext(StoreProjectContextRequest $request, Entity $entity): RedirectResponse
    {
        $data = $request->validated();

        session()->put('context', $data['context']);

        if ($data['context'] === 'new') {
            session()->forget('ancestor');
        }

        if ($data['context'] === 'follow-up') {
            session()->put('ancestor', $data['ancestor']);
        }

        return redirect(\localized_route('projects.create', ['entity' => $entity, 'step' => 2]));
    }

    /**
     * Store a new project's initial focus in the session.
     *
     * @param  \App\Http\Requests\StoreProjectFocusRequest  $request
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFocus(StoreProjectFocusRequest $request, Entity $entity): RedirectResponse
    {
        $data = $request->validated();

        session()->put('focus', $data['focus']);

        return redirect(\localized_route('projects.create', ['entity' => $entity, 'step' => 3]));
    }

    /**
     * Store a new project's languages in the session.
     *
     * @param  \App\Http\Requests\StoreProjectLanguagesRequest  $request
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLanguages(StoreProjectLanguagesRequest $request, Entity $entity): RedirectResponse
    {
        $data = $request->validated();

        session()->put('languages', $data['languages']);

        return redirect(\localized_route('projects.create', ['entity' => $entity, 'step' => 4]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreProjectRequest  $request
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProjectRequest $request, Entity $entity): RedirectResponse
    {
        $data = $request->validated();

        $project = Project::create($data);

        flash(__('Your project has been created.'), 'success');

        return redirect(\localized_route('projects.edit', ['project' => $project]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project): View
    {
        if ($project->checkStatus('draft')) {
            return view('projects.show-draft', ['project' => $project]);
        }

        return view('projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', ['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->fill($request->validated());
        $project->save();

        flash(__('Your project has been updated.'), 'success');

        return redirect(\localized_route('projects.show', $project));
    }

    /**
     * Update the specified resource's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePublicationStatus(Request $request, Project $project): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $project->published_at = null;
            $project->save();

            flash(__('Your project has been unpublished.'), 'success');
        } elseif ($request->input('publish')) {
            $project->published_at = date('Y-m-d h:i:s', time());
            $project->save();

            flash(__('Your project has been published.'), 'success');
        }

        return redirect(\localized_route('projects.show', $project));
    }

    /**
     * Update the progress of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProgress(Request $request, Project $project): RedirectResponse
    {
        if ($request->input('complete')) {
            $project->update([$request->input('substep') => true]);

            flash(__('Your project has been updated.'), 'success');
        } elseif ($request->input('incomplete')) {
            $project->update([$request->input('substep') => false]);

            flash(__('Your project has been updated.'), 'success');
        }

        return redirect(\localized_route('projects.manage', $project));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\DestroyProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyProjectRequest $request, Project $project): RedirectResponse
    {
        $project->delete();

        flash(__('Your project has been deleted.'), 'success');

        return redirect(\localized_route('dashboard'));
    }

    /**
     * Display the management UI for the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function manage(Request $request, Project $project): View
    {
        return view('projects.entity-dashboard', [
            'project' => $project,
            'steps' => $project->getEntitySteps(),
            'substeps' => $project->getEntitySubsteps(),
            'step' => request()->get('step') ? request()->get('step') : $project->currentEntityStep(),
        ]);
    }

    /**
     * Display the participant UI for the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function participate(Request $request, Project $project): View
    {
        return view('projects.participant-dashboard', [
            'project' => $project,
            'steps' => $project->getParticipantSteps(),
            'substeps' => $project->getParticipantSubsteps(),
            'step' => request()->get('step') ? request()->get('step') : $project->currentParticipantStep(),
        ]);
    }

    /**
     * Manage participants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function findInterestedCommunityMembers(Request $request, Project $project): View
    {
        $projectPaymentMethods = $project->paymentMethods()->pluck('id')->toArray();

        return view('projects.find-participants', [
            'project' => $project,
            'subtitle' => __('Interested in this project'),
            'communityMembers' => CommunityMember::whereDoesntHave('projects', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->whereHas('projectsOfInterest', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->with(['paymentMethods', 'sectors', 'impacts'])->paginate(20),
        ]);
    }

    /**
     * Manage participants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function findRelatedCommunityMembers(Request $request, Project $project): View
    {
        $projectPaymentMethods = $project->paymentMethods()->pluck('id')->toArray();

        return view('projects.find-participants', [
            'project' => $project,
            'subtitle' => __('From similar projects'),
            'communityMembers' => CommunityMember::whereDoesntHave('projects', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->with(['paymentMethods', 'sectors', 'impacts'])->paginate(20),
        ]);
    }

    /**
     * Manage participants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function findAllCommunityMembers(Request $request, Project $project): View
    {
        $projectPaymentMethods = $project->paymentMethods()->pluck('id')->toArray();

        return view('projects.find-participants', [
            'project' => $project,
            'subtitle' => __('Browse all community members'),
            'communityMembers' => CommunityMember::whereDoesntHave('projects', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->with(['paymentMethods', 'sectors', 'impacts'])->paginate(20),
        ]);
    }

    /**
     * Manage participants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addParticipant(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'participant_id' => 'required|integer',
        ]);

        $communityMember = CommunityMember::find($request->input('participant_id'));

        $project->participants()->attach($request->input('participant_id'));
        $project->update(['found_participants' => false]);

        flash(__(':name has been added to your participant shortlist.', ['name' => $communityMember->name]), 'success');

        return redirect()->back();
    }

    /**
     * Update existing participant attachments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateParticipants(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'participant_ids' => 'required|array',
            'status' => 'required|string|in:shortlisted,requested,confirmed,removed,exited',
        ]);

        foreach ($request->input('participant_ids') as $communityMember_id) {
            $project->participants()->updateExistingPivot(
                $communityMember_id,
                ['status' => $request->input('status')]
            );
        }

        return redirect(\localized_route('projects.manage', $project));
    }

    /**
     * Update an existing participant attachment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateParticipant(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'participant_id' => 'required|integer',
            'status' => 'required|string|in:shortlisted,requested,confirmed,removed,exited',
        ]);

        $communityMember = CommunityMember::find($request->input('participant_id'));

        $project->participants()->updateExistingPivot(
            $request->input('participant_id'),
            ['status' => $request->input('status')]
        );

        switch ($request->input('status')) {
            case 'requested':
                flash(__('You have requested :name’s participation in your project.', ['name' => $communityMember->name]), 'success');
                $project->update(['confirmed_participants' => false]);

                break;
            case 'confirmed':
                flash(__(':name’s participation in your project is now confirmed!', ['name' => $communityMember->name]), 'success');

                break;
            case 'removed':
                flash(__('You have removed :name from your project.', ['name' => $communityMember->name]), 'success');

                break;
            case 'exited':
                flash(__(':name has left your project.', ['name' => $communityMember->name]), 'success');

                break;
        }

        return redirect()->back();
    }

    /**
     * Remove an existing participant attachment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeParticipant(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'participant_id' => 'required|integer',
        ]);

        $communityMember = CommunityMember::find($request->input('participant_id'));

        $project->participants()->detach($request->input('participant_id'));

        flash(__(':name has been removed from your participant shortlist.', ['name' => $communityMember->name]), 'success');

        return redirect()->back();
    }

    /**
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\View\View
     */
    public function indexProjectUpdates(Project $project): View
    {
        return view('projects.index-updates', [
            'project' => $project,
        ]);
    }

    /**
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\View\View
     */
    public function createProjectUpdate(Project $project): View
    {
        return view('projects.create-update', [
            'project' => $project,
            'steps' => $project->getEntitySteps(),
        ]);
    }
}
