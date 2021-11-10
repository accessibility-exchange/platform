<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConsultantRequest;
use App\Http\Requests\DestroyConsultantRequest;
use App\Http\Requests\UpdateConsultantRequest;
use App\Models\Consultant;
use App\Statuses\ConsultantStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('consultants.index', [
            'consultants' => Consultant::status(new ConsultantStatus('published'))->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create', Consultant::class);

        return view('consultants.create', [
            'regions' => get_regions(['CA'], \locale()),
            'creators' => [
                'self' => __('I’m creating it myself'),
                'other' => __('Someone else is creating it for me'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateConsultantRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateConsultantRequest $request): RedirectResponse
    {
        $consultant = Consultant::create($request->safe()->except('picture'));

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $consultant->addMediaFromRequest('picture')->toMediaCollection('picture');
        }

        flash(__('Your draft consultant page has been saved.'), 'success');

        return redirect(\localized_route('consultants.show', ['consultant' => $consultant]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\View\View
     */
    public function show(Consultant $consultant): View
    {
        if ($consultant->checkStatus('draft')) {
            return view('consultants.show-draft', ['consultant' => $consultant]);
        }

        return view('consultants.show', ['consultant' => $consultant]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\View\View
     */
    public function edit(Consultant $consultant): View
    {
        return view('consultants.edit', [
            'consultant' => $consultant,
            'regions' => get_regions(['CA'], \locale()),
            'creators' => [
                'self' => __('I’m creating it myself'),
                'other' => __('Someone else is creating it for me'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConsultantRequest  $request
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateConsultantRequest $request, Consultant $consultant): RedirectResponse
    {
        $consultant->fill($request->safe()->except('picture'));

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $consultant->addMediaFromRequest('picture')->toMediaCollection('picture');
        } elseif (! $request->hasFile('picture')) {
            $consultant->clearMediaCollection('picture');
        }

        $consultant->save();

        if ($consultant->checkStatus('draft')) {
            flash(__('Your draft consultant page has been updated.'), 'success');
        }

        flash(__('Your consultant page has been updated.'), 'success');

        return redirect(\localized_route('consultants.show', $consultant));
    }

    /**
     * Update the specified resource's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePublicationStatus(Request $request, Consultant $consultant): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $consultant->published_at = null;
            $consultant->save();

            flash(__('Your consultant page has been unpublished.'), 'success');
        } elseif ($request->input('publish')) {
            $consultant->published_at = date('Y-m-d h:i:s', time());
            $consultant->save();

            flash(__('Your consultant page has been published.'), 'success');
        }

        return redirect(\localized_route('consultants.show', $consultant));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyConsultantRequest  $request
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyConsultantRequest $request, Consultant $consultant): RedirectResponse
    {
        $consultant->delete();

        flash(__('Your consultant page has been deleted.'), 'success');

        return redirect(\localized_route('dashboard'));
    }

    /**
     * Express interest in a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function expressInterest(Request $request, Consultant $consultant): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
        ]);

        $consultant->projectsOfInterest()->attach($request->input('project_id'));

        flash(__('You have expressed your interest in this project.'), 'success');

        return redirect()->back();
    }

    /**
     * Remove interest in a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeInterest(Request $request, Consultant $consultant): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
        ]);

        $consultant->projectsOfInterest()->detach($request->input('project_id'));

        flash(__('You have removed your expression of interest in this project.'), 'success');

        return redirect()->back();
    }
}
