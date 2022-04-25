<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProjectRequest;
use App\Http\Requests\StoreProjectContextRequest;
use App\Http\Requests\StoreProjectFocusRequest;
use App\Http\Requests\StoreProjectLanguagesRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\UpdateProjectTeamRequest;
use App\Models\CommunityMember;
use App\Models\Impact;
use App\Models\Project;
use App\Statuses\ProjectStatus;
use CommerceGuys\Intl\Language\LanguageRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
                ->with('regulatedOrganization')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
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
            'languages' => [
                '' => __('Choose a language…'),

            ] + Arr::sort($languages),
            'impacts' => Impact::pluck('name', 'id')->toArray(),
            'projectable' => Auth::user()->projectable(),
            'ancestors' => [
                '' => __('Choose a project…'),
            ] + Arr::sort(Auth::user()->projectable()->projects->pluck('name', 'id')->toArray()),
        ]);
    }

    /**
     * Store a new project's context in the session.
     *
     * @param  \App\Http\Requests\StoreProjectContextRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeContext(StoreProjectContextRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('context', $data['context']);

        if ($data['context'] === 'new') {
            session()->forget('ancestor');
        }

        if ($data['context'] === 'follow-up') {
            session()->put('ancestor', $data['ancestor']);
        }

        return redirect(\localized_route('projects.create', ['step' => 2]));
    }

    /**
     * Store a new project's initial focus in the session.
     *
     * @param  \App\Http\Requests\StoreProjectFocusRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFocus(StoreProjectFocusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('focus', $data['focus']);

        return redirect(\localized_route('projects.create', ['step' => 3]));
    }

    /**
     * Store a new project's languages in the session.
     *
     * @param  \App\Http\Requests\StoreProjectLanguagesRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLanguages(StoreProjectLanguagesRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('languages', $data['languages']);

        return redirect(\localized_route('projects.create', ['step' => 4]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProjectRequest $request): RedirectResponse
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
     * Update the specified resource's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePublicationStatus(Request $request, Project $project): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $project->unpublish();
        } elseif ($request->input('publish')) {
            $project->publish();
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
        return view('projects.organizer-dashboard', ['project' => $project]);
    }
}
