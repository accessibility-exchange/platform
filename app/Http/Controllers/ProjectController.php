<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProjectRequest;
use App\Http\Requests\StoreProjectContextRequest;
use App\Http\Requests\StoreProjectFocusRequest;
use App\Http\Requests\StoreProjectLanguagesRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectOutcomesRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\UpdateProjectTeamRequest;
use App\Models\CommunityMember;
use App\Models\Entity;
use App\Models\Impact;
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
            'impacts' => Impact::pluck('name', 'id')->toArray(),
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

        $data['languages'] = session()->get('languages');

        $project = Project::create($data);

        flash(__('Your project has been created.'), 'success');

        return redirect(\localized_route('projects.edit', ['project' => $project, 'step' => 2]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project): View
    {
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
        $languages = (new LanguageRepository)->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key] = $language->getName();
        }

        $languages = $languages + [
            'ase' => __('American Sign Language'),
            'fcs' => __('Quebec Sign Language'),
        ];

        return view('projects.edit', [
            'project' => $project,
            'languages' => [
                '' => __('Choose a language…'),
            ] + Arr::sort($languages),
            'impacts' => Impact::pluck('name', 'id')->toArray(),
            'consultants' => [
                '' => __('Choose an accessibility consultant…'),
            ] + CommunityMember::pluck('name', 'id')->toArray(), // TODO: Only select accessibility consultants
        ]);
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
        $data = $request->validated();

        $project->fill($data);
        $project->save();

        if (isset($data['impacts'])) {
            $project->impacts()->sync($data['impacts'] ?? []);
        }

        return $project->handleUpdateRequest($request, 1);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectTeamRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTeam(UpdateProjectTeamRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        $project->fill($data);
        $project->save();

        return $project->handleUpdateRequest($request, 2);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectOutcomesRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOutcomes(UpdateProjectOutcomesRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        $project->fill($data);
        $project->save();

        return $project->handleUpdateRequest($request, 1);
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
        return view('projects.entity-dashboard', ['project' => $project]);
    }
}
