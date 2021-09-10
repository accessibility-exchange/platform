<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConsultantRequest;
use App\Http\Requests\DestroyConsultantRequest;
use App\Http\Requests\UpdateConsultantRequest;
use App\Models\Consultant;
use Illuminate\Http\Request;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('consultants.index', ['consultants' => Consultant::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Consultant::class);

        return view('consultants.create', [
            'regions' => get_regions(['CA'], \locale()),
            'creators' => [
                'self' => __('consultant.label_creator_self'),
                'other' => __('consultant.label_creator_other'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateConsultantRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateConsultantRequest $request)
    {
        $consultant = Consultant::create($request->validated());

        $consultant->addMedia($request->file('picture'))->toMediaCollection('picture');

        flash(__('consultant.save_draft_succeeded'), 'success');

        return redirect(\localized_route('consultants.show', ['consultant' => $consultant]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\View\View
     */
    public function show(Consultant $consultant)
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
    public function edit(Consultant $consultant)
    {
        return view('consultants.edit', [
            'consultant' => $consultant,
            'regions' => get_regions(['CA'], \locale()),
            'creators' => [
                'self' => __('consultant.label_creator_self'),
                'other' => __('consultant.label_creator_other'),
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
    public function update(UpdateConsultantRequest $request, Consultant $consultant)
    {
        $consultant->fill($request->validated());

        $consultant->addMedia($request->file('picture'))->toMediaCollection('picture');

        $consultant->save();

        if ($consultant->checkStatus('draft')) {
            flash(__('consultant.update_draft_succeeded'), 'success');
        }

        flash(__('consultant.update_succeeded'), 'success');

        return redirect(\localized_route('consultants.show', $consultant));
    }

    /**
     * Update the specified resource's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultant  $consultant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Consultant $consultant)
    {
        if ($request->input('unpublish')) {
            $consultant->published_at = null;
            $consultant->save();

            flash(__('consultant.unpublish_succeeded'), 'success');
        } elseif ($request->input('publish')) {
            $consultant->published_at = date('Y-m-d h:i:s', time());
            $consultant->save();

            flash(__('consultant.publish_succeeded'), 'success');
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
    public function destroy(DestroyConsultantRequest $request, Consultant $consultant)
    {
        $consultant->delete();

        flash(__('consultant.destroy_succeeded'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
