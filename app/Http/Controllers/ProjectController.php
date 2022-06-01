<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProjectRequest;
use App\Http\Requests\StoreProjectContextRequest;
use App\Http\Requests\StoreProjectFocusRequest;
use App\Http\Requests\StoreProjectLanguagesRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\UpdateProjectTeamRequest;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\Project;
use App\Statuses\ProjectStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
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
     * @return View
     */
    public function create(): View
    {
        return view('projects.create', [
            'languages' => [
                '' => __('Choose a language…'),

            ] + get_available_languages(true),
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
     * @param StoreProjectContextRequest $request
     * @return RedirectResponse
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

        return redirect(localized_route('projects.create', ['step' => 2]));
    }

    /**
     * Store a new project's initial focus in the session.
     *
     * @param StoreProjectFocusRequest $request
     * @return RedirectResponse
     */
    public function storeFocus(StoreProjectFocusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('focus', $data['focus']);

        return redirect(localized_route('projects.create', ['step' => 3]));
    }

    /**
     * Store a new project's languages in the session.
     *
     * @param StoreProjectLanguagesRequest $request
     * @return RedirectResponse
     */
    public function storeLanguages(StoreProjectLanguagesRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('languages', $data['languages']);

        return redirect(localized_route('projects.create', ['step' => 4]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProjectRequest $request
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['languages'] = session()->get('languages');

        $project = Project::create($data);

        flash(__('Your project has been created.'), 'success');

        return redirect(localized_route('projects.edit', ['project' => $project, 'step' => 2]));
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project): View
    {
        return view('projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return View
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', [
            'project' => $project,
            'languages' => [
                '' => __('Choose a language…'),
            ] + get_available_languages(true),
            'impacts' => Impact::pluck('name', 'id')->toArray(),
            'consultants' => [
                '' => __('Choose an accessibility consultant…'),
            ] + Individual::pluck('name', 'id')->toArray(), // TODO: Only select accessibility consultants
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return RedirectResponse
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
     * @param UpdateProjectTeamRequest $request
     * @param Project $project
     * @return RedirectResponse
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
     * @param Request $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function updatePublicationStatus(Request $request, Project $project): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $project->unpublish();
        } elseif ($request->input('publish')) {
            $project->publish();
        }

        return redirect(localized_route('projects.show', $project));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyProjectRequest $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(DestroyProjectRequest $request, Project $project): RedirectResponse
    {
        $project->delete();

        flash(__('Your project has been deleted.'), 'success');

        return redirect(localized_route('dashboard'));
    }

    /**
     * Display the management UI for the specified resource.
     *
     * @param Request $request
     * @param Project $project
     * @return View
     */
    public function manage(Request $request, Project $project): View
    {
        return view('projects.organizer-dashboard', ['project' => $project]);
    }
}
