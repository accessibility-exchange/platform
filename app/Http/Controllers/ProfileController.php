<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProfileRequest;
use App\Http\Requests\DestroyProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('profiles.index', ['profiles' => Profile::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Profile::class);

        return view('profiles.create', [
            'regions' => get_regions(['CA'], \locale()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateProfileRequest $request)
    {
        $profile = Profile::create($request->validated());

        if ($request->input('save_draft')) {
            $profile['status'] = 'draft';
            flash(__('profile.save_draft_succeeded'), 'success');
        } elseif ($request->input('publish')) {
            $profile['status'] = 'published';
            flash(__('profile.publish_succeeded'), 'success');
        }


        return redirect(\localized_route('profiles.show', ['profile' => $profile]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\View\View
     */
    public function show(Profile $profile)
    {
        return view('profiles.show', ['profile' => $profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\View\View
     */
    public function edit(Profile $profile)
    {
        return view('profiles.edit', [
            'profile' => $profile,
            'regions' => get_regions(['CA'], \locale()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProfileRequest  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $profile->fill($request->validated());

        ray($request->validated());

        $profile->save();

        flash(__('profile.update_succeeded'), 'success');

        return redirect(\localized_route('profiles.show', $profile));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unpublish(Request $request, Profile $profile)
    {
        $profile->status = 'draft';

        $profile->save();

        flash(__('profile.unpublish_succeeded'), 'success');

        return redirect(\localized_route('profiles.show', $profile));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyProfileRequest  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyProfileRequest $request, Profile $profile)
    {
        $profile->delete();

        flash(__('profile.destroy_succeeded'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
