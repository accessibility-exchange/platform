<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\DestroyProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Consultant;
use App\Models\Entity;
use App\Models\Project;
use App\Statuses\ProjectStatus;
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
            'projects' => Project::status(new ProjectStatus('published'))
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
            'projects' => Project::status(new ProjectStatus('published'))
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
            $project->published_at = null;
            $project->save();

            flash(__('project.unpublish_succeeded'), 'success');
        } elseif ($request->input('publish')) {
            $project->published_at = date('Y-m-d h:i:s', time());
            $project->save();

            flash(__('project.publish_succeeded'), 'success');
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
    public function updateProgress(Request $request, Project $project)
    {
        if ($request->input('complete')) {
            $project->update([$request->input('substep') => true]);

            flash(__('project.update_succeeded'), 'success');
        } elseif ($request->input('incomplete')) {
            $project->update([$request->input('substep') => false]);

            flash(__('project.update_succeeded'), 'success');
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
    public function destroy(DestroyProjectRequest $request, Project $project)
    {
        $project->delete();

        flash(__('project.destroy_succeeded'), 'success');

        return redirect(\localized_route('dashboard'));
    }

    /**
     * Display the management UI for the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function manage(Request $request, Project $project)
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
    public function participate(Request $request, Project $project)
    {
        return view('projects.consultant-dashboard', [
            'project' => $project,
            'steps' => $project->getConsultantSteps(),
            'substeps' => $project->getConsultantSubsteps(),
            'step' => request()->get('step') ? request()->get('step') : $project->currentConsultantStep(),
        ]);
    }

    /**
     * Manage consultants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function findInterestedConsultants(Request $request, Project $project)
    {
        $projectPaymentMethods = $project->paymentMethods()->pluck('id')->toArray();

        return view('projects.find-consultants', [
            'project' => $project,
            'subtitle' => __('Interested in this project'),
            'consultants' => Consultant::whereDoesntHave('projects', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->whereHas('projectsOfInterest', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->with(['paymentMethods', 'sectors', 'impacts'])->paginate(20),
        ]);
    }

    /**
     * Manage consultants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function findRelatedConsultants(Request $request, Project $project)
    {
        $projectPaymentMethods = $project->paymentMethods()->pluck('id')->toArray();

        return view('projects.find-consultants', [
            'project' => $project,
            'subtitle' => __('From similar projects'),
            'consultants' => Consultant::whereDoesntHave('projects', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->with(['paymentMethods', 'sectors', 'impacts'])->paginate(20),
        ]);
    }

    /**
     * Manage consultants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function findAllConsultants(Request $request, Project $project)
    {
        $projectPaymentMethods = $project->paymentMethods()->pluck('id')->toArray();

        return view('projects.find-consultants', [
            'project' => $project,
            'subtitle' => __('Browse all consultants'),
            'consultants' => Consultant::whereDoesntHave('projects', function ($query) use ($project) {
                $query->where('id', '=', $project->id);
            })->with(['paymentMethods', 'sectors', 'impacts'])->paginate(20),
        ]);
    }

    /**
     * Manage consultants for the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function addConsultant(Request $request, Project $project)
    {
        $validated = $request->validate([
            'consultant_id' => 'required|integer',
        ]);

        $consultant = Consultant::find($request->input('consultant_id'));

        $project->consultants()->attach($request->input('consultant_id'));
        $project->update(['found_consultants' => false]);

        flash(__(':name has been added to your consultant shortlist.', ['name' => $consultant->name]), 'success');

        return redirect()->back();
    }

    /**
     * Update existing consultant attachments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function updateConsultants(Request $request, Project $project)
    {
        $validated = $request->validate([
            'consultant_ids' => 'required|array',
            'status' => 'required|string|in:shortlisted,requested,confirmed,removed,exited',
        ]);

        foreach ($request->input('consultant_ids') as $consultant_id) {
            $project->consultants()->updateExistingPivot(
                $consultant_id,
                ['status' => $request->input('status')]
            );
        }

        return redirect(\localized_route('projects.manage', $project));
    }

    /**
     * Update an existing consultant attachment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function updateConsultant(Request $request, Project $project)
    {
        $validated = $request->validate([
            'consultant_id' => 'required|integer',
            'status' => 'required|string|in:shortlisted,requested,confirmed,removed,exited',
        ]);

        $consultant = Consultant::find($request->input('consultant_id'));

        $project->consultants()->updateExistingPivot(
            $request->input('consultant_id'),
            ['status' => $request->input('status')]
        );

        switch ($request->input('status')) {
            case 'requested':
                flash(__('You have requested :name’s participation in your project.', ['name' => $consultant->name]), 'success');
                $project->update(['confirmed_consultants' => false]);

                break;
            case 'confirmed':
                flash(__(':name’s participation in your project is now confirmed!', ['name' => $consultant->name]), 'success');

                break;
            case 'removed':
                flash(__('You have removed :name from your project.', ['name' => $consultant->name]), 'success');

                break;
            case 'exited':
                flash(__(':name has left your project.', ['name' => $consultant->name]), 'success');

                break;
        }


        return redirect()->back();
    }

    /**
     * Remove an existing consultant attachment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function removeConsultant(Request $request, Project $project)
    {
        $validated = $request->validate([
            'consultant_id' => 'required|integer',
        ]);

        $consultant = Consultant::find($request->input('consultant_id'));

        $project->consultants()->detach($request->input('consultant_id'));

        flash(__(':name has been removed from your consultant shortlist.', ['name' => $consultant->name]), 'success');

        return redirect()->back();
    }
}
