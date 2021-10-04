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
        return view('projects.manage', [
            'project' => $project,
            'steps' => [
                1 => __('Publish your project'),
                2 => __('Build your consulting team'),
                3 => __('Learn how to work together'),
                4 => __('Hold consultations'),
                5 => __('Write your report'),
            ],
            'substeps' => [
                1 => [
                    1 => [
                        'link' => \localized_route('projects.edit', $project),
                        'label' => __('Publish project page'),
                        'description' => false,
                        'status' => $project->checkStatus('published') ? 'complete' : 'in-progress',
                    ],
                ],
                2 => [
                    1 => [
                        'link' => \localized_route('projects.find-interested-consultants', $project),
                        'label' => __('Find consultants'),
                        'description' => false,
                        'status' => $project->found_consultants ?? null,
                    ],
                    2 => [
                        'link' => "#",
                        'label' => __('Confirm consultants’ participation'),
                        'description' => false,
                        'status' => $project->confirmed_consultants ?? null,
                    ],
                ],
                3 => [
                    1 => [
                        'link' => "#",
                        'label' => __('Schedule the meeting'),
                        'description' => false,
                        'status' => $project->scheduled_planning_meeting ?? null,
                    ],
                    2 => [
                        'link' => "#",
                        'label' => __('Contact consultant team'),
                        'description' => false,
                        'status' => $project->notified_of_planning_meeting ?? null,
                    ],
                    3 => [
                        'link' => "#",
                        'label' => __('Prepare a project orientation'),
                        'description' => false,
                        'status' => $project->prepared_project_orientation ?? null,
                    ],
                    4 => [
                        'link' => "#",
                        'label' => __('Prepare contracts and other legal documents'),
                        'description' => false,
                        'status' => $project->prepared_contractual_documents ?? null,
                    ],
                    5 => [
                        'link' => "#",
                        'label' => __('Provide access accommodations and book service providers'),
                        'description' => false,
                        'status' => $project->booked_access_services_for_planning ?? null,
                    ],
                    6 => [
                        'link' => "#",
                        'label' => __('Hold the meeting'),
                        'description' => false,
                        'status' => $project->finished_planning_meeting ?? null,
                    ],
                ],
                4 => [
                    1 => [
                        'link' => "#",
                        'label' => __('Schedule the meetings'),
                        'description' => false,
                        'status' => $project->scheduled_consultation_meetings ?? null,
                    ],
                    2 => [
                        'link' => "#",
                        'label' => __('Contact consultant team'),
                        'description' => false,
                        'status' => $project->notified_of_consultation_meetings ?? null,
                    ],
                    3 => [
                        'link' => "#",
                        'label' => __('Prepare consultation materials'),
                        'description' => false,
                        'status' => $project->prepared_consultation_materials ?? null,
                    ],
                    4 => [
                        'link' => "#",
                        'label' => __('Provide access accommodations and book service providers'),
                        'description' => false,
                        'status' => $project->booked_access_services_for_consultations ?? null,
                    ],
                    5 => [
                        'link' => "#",
                        'label' => __('Hold the meetings'),
                        'description' => false,
                        'status' => $project->finished_consultation_meetings ?? null,
                    ],
                ],
                5 => [
                    1 => [
                        'link' => "#",
                        'label' => __('Prepare your accessibility plan'),
                        'description' => false,
                        'status' => $project->prepared_accessibility_plan ?? null,
                    ],
                    2 => [
                        'link' => "#",
                        'label' => __('Prepare your follow-up plan'),
                        'description' => false,
                        'status' => $project->prepared_follow_up_plan ?? null,
                    ],
                    3 => [
                        'link' => "#",
                        'label' => __('Share your accessibility plan and follow-up plan with your consultant team'),
                        'description' => false,
                        'status' => $project->shared_plans_with_consultants ?? null,
                    ],
                    4 => [
                        'link' => "#",
                        'label' => __('Publish your accessibility plan (optional)'),
                        'description' => false,
                        'status' => $project->published_accessibility_plan ?? null,
                    ],
                ],
            ],
            'step' => request()->get('step') ? request()->get('step') : $project->currentStep(),
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
