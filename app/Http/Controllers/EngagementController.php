<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Models\Engagement;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EngagementController extends Controller
{
    public function create(Project $project): View
    {
        return view('engagements.create', [
            'project' => $project,
            'formats' => [['value' => 'survey', 'label' => __('Survey')]],
        ]);
    }

    public function store(StoreEngagementRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        $engagement = Engagement::create($data);

        flash(__('Your engagement has been created.'), 'success');

        return redirect(localized_route('engagements.manage', ['engagement' => $engagement, 'project' => $project]));
    }

    public function show(Project $project, Engagement $engagement)
    {
        return view('engagements.show', ['engagement' => $engagement, 'project' => $project]);
    }

    public function edit(Project $project, Engagement $engagement)
    {
        return view('engagements.edit', ['engagement' => $engagement, 'project' => $project]);
    }

    public function update(UpdateEngagementRequest $request, Project $project, Engagement $engagement)
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(localized_route('engagements.manage', ['engagement' => $engagement, 'project' => $project]));
    }

    public function manage(Project $project, Engagement $engagement)
    {
        return view('engagements.manage', ['engagement' => $engagement, 'project' => $project]);
    }

    public function participate(Project $project, Engagement $engagement)
    {
        return view('engagements.participate', ['engagement' => $engagement, 'project' => $project]);
    }
}
