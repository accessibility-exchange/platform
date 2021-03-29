<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Project;
use App\Http\Requests\CreateProjectRequest;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('projects.index', ['projects' => Project::orderBy('name')->get()]);
    }

    /**
     * Display a listing of the resource within a specific entity.
     *
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\View\View
     */
    public function entityIndex(Entity $entity)
    {
        return view('projects.entity-index', [
            'projects' => Project::orderBy('name')
                ->where('entity_id', $entity->id)
                ->get(),
            'entity' => $entity
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\View\View
     */
    public function create(Entity $entity)
    {
        return view('projects.create', ['entity' => $entity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CreateProjectRequest  $request
     * @param \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateProjectRequest $request, Entity $entity)
    {
        $data = $request->validated();
        $data['start_date'] = Carbon::createFromFormat('Y-m-d', $data['start_date']);
        $data['end_date'] = $data['end_date']
            ? Carbon::createFromFormat('Y-m-d', $data['end_date'])
            : $data['end_date'];

        $project = Project::create($data);

        flash(__('project.create_succeeded'), 'success');

        return redirect(localized_route('projects.show', ['project' => $project]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        return view('projects.show', ['project' => $project]);
    }
}
