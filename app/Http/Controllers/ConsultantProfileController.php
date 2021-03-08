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
        $consultantProfile = ConsultantProfile::create($request->validated());

        flash(__('consultant-profile.create_succeeded'), 'success');

        return redirect(localized_route('consultant-profiles.show', ['consultantProfile' => $consultantProfile]));
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
        $consultantProfile->fill($request->validated());
        $consultantProfile->save();

        flash(__('consultant-profile.update_succeeded'), 'success');

        return redirect(localized_route('consultant-profiles.show', ['consultantProfile' => $consultantProfile]));
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

        flash(__('consultant-profile.destroy_succeeded'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
