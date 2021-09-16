<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\DestroyProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Entity;
use App\Models\Project;
use App\States\Draft;
use App\States\Project\Preparing;
use App\States\Published;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::where('state', Published::class)
                ->with('entity')
                ->orderBy('name')
                ->get(),
        ]);
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
            'projects' => Project::where('state', Published::class)
                ->where('entity_id', $entity->id)
                ->orderBy('name')
                ->get(),
            'entity' => $entity,
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

        return redirect(\localized_route('projects.show', ['project' => $project]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        if (is_a($project->publication_state, Draft::class)) {
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
    public function edit(Project $project)
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
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->fill($request->validated());
        $project->save();

        flash(__('project.update_succeeded'), 'success');

        return redirect(\localized_route('projects.show', $project));
    }

    /**
     * Update the specified resource's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePublicationStatus(Request $request, Project $project)
    {
        if ($request->input('unpublish')) {
            $project->publication_state->transitionTo(Draft::class);

            flash(__('project.unpublish_succeeded'), 'success');
        } elseif ($request->input('publish')) {
            $project->publication_state->transitionTo(Published::class);

            if (! $project->state) {
                $project->state = Preparing::class;
                $project->save();
            }

            flash(__('project.publish_succeeded'), 'success');
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
    public function destroy(DestroyProjectRequest $request, Project $project)
    {
        $project->delete();

        flash(__('project.destroy_succeeded'), 'success');

        return redirect(\localized_route('dashboard'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function manage(Request $request, Project $project)
    {
        $step = 1;

        if ($project->publication_state->slug() === 'published') {
            $step = 2;
        } elseif (in_array($project->state->slug(), ['writing_report', 'completed'])) {
            $step = 6;
        } elseif ($project->state->slug() === 'holding_consultations') {
            $step = 5;
        } elseif ($project->state->slug() === 'negotiating_consultations') {
            $step = 4;
        } elseif ($project->state->slug() === 'confirming_consultants') {
            $step = 3;
        } elseif ($project->publication_state->slug() === 'published') {
            $step = 2;
        }

        return view('projects.manage', [
            'project' => $project,
            'steps' => [
                1 => __('Publish your project'),
                2 => __('Prepare for consultations'),
                3 => __('Build your consulting team'),
                4 => __('Learn how to work together'),
                5 => __('Hold consultations'),
                6 => __('Write your report'),
            ],
            'step' => request()->get('step') ? request()->get('step') : $step,
        ]);
    }

    /**
     * Update the specified resource's progress.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProgress(Request $request, Project $project)
    {
        if ($request->input('step') && $request->input('substep')) {
            $step = $request->input('step');
            $substep = $request->input('substep');

            if ($request->input('undo')) {
                $progress = $project->progress[$step];
                unset($progress[array_search($substep, $progress)]);
                $project->update(
                    [
                        "progress->{$step}" => $progress,
                    ]
                );
            } else {
                $project->update(
                    [
                        "progress->{$step}" => array_unique(
                            array_merge(
                                $project->progress[$step] ?? [],
                                [$request->input('substep')]
                            )
                        ),
                    ]
                );
            }

            flash(__('Progress updated!'), 'success');
        }

        return redirect(\localized_route('projects.manage', $project));
    }
}
