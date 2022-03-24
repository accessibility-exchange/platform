<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Models\Engagement;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EngagementController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function create(Project $project): View
    {
        return view('engagements.create', ['project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEngagementRequest  $request
     * @param \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEngagementRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        $engagement = Engagement::create($data);

        flash(__('Your engagement has been created.'), 'success');

        return redirect(\localized_route('engagements.manage', ['engagement' => $engagement, 'project' => $project]));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project  $project
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Engagement $engagement)
    {
        return view('engagements.show', ['engagement' => $engagement, 'project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Project  $project
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Engagement $engagement)
    {
        return view('engagements.edit', ['engagement' => $engagement, 'project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEngagementRequest  $request
     * @param \App\Models\Project  $project
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEngagementRequest $request, Project $project, Engagement $engagement)
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(\localized_route('engagements.manage', ['engagement' => $engagement, 'project' => $project]));
    }

    /**
     * Manage the specified resource.
     *
     * @param \App\Models\Project  $project
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function manage(Project $project, Engagement $engagement)
    {
        return view('engagements.manage', ['engagement' => $engagement, 'project' => $project]);
    }
}
