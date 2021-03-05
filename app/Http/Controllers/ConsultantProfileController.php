<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConsultantProfileRequest;
use App\Http\Requests\UpdateConsultantProfileRequest;
use App\Http\Requests\DestroyConsultantProfileRequest;
use App\Models\ConsultantProfile;
use Illuminate\Http\Request;

class ConsultantProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('consultant-profiles.index', ['consultantProfiles' => ConsultantProfile::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', ConsultantProfile::class);

        return view('consultant-profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateConsultantProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateConsultantProfileRequest $request)
    {
        $validated = $request->validated();

        $consultantProfile = ConsultantProfile::create($validated);

        return redirect(localized_route('consultant-profiles.show', ['consultantProfile' => $consultantProfile]))
            ->with('status', 'consultant-profile-create-success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConsultantProfile  $consultantProfile
     * @return \Illuminate\View\View
     */
    public function show(ConsultantProfile $consultantProfile)
    {
        return view('consultant-profiles.show', ['consultantProfile' => $consultantProfile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConsultantProfile  $consultantProfile
     * @return \Illuminate\View\View
     */
    public function edit(ConsultantProfile $consultantProfile)
    {
        return view('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConsultantProfileRequest  $request
     * @param  \App\Models\ConsultantProfile  $consultantProfile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateConsultantProfileRequest $request, ConsultantProfile $consultantProfile)
    {
        $validated = $request->validated();

        $consultantProfile->fill($validated);
        $consultantProfile->save();

        return redirect(localized_route('consultant-profiles.show', ['consultantProfile' => $consultantProfile]))
            ->with('status', 'consultant-profile-update-success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyConsultantProfileRequest  $request
     * @param  \App\Models\ConsultantProfile  $consultantProfile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyConsultantProfileRequest $request, ConsultantProfile $consultantProfile)
    {
        $consultantProfile->delete();

        return redirect(localized_route('welcome'))->with('status', 'consultant-profile-destroy-succeeded');
    }
}
