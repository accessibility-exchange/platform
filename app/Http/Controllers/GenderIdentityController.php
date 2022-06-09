<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenderIdentityRequest;
use App\Http\Requests\UpdateGenderIdentityRequest;
use App\Models\GenderIdentity;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class GenderIdentityController extends Controller
{
    public function index(): View
    {
        return view('gender-identities.index', [
            'genderIdentities' => GenderIdentity::all(),
        ]);
    }

    public function create(): View
    {
        return view('gender-identities.create');
    }

    public function store(StoreGenderIdentityRequest $request): RedirectResponse
    {
        GenderIdentity::create($request->validated());

        flash(__('The gender identity has been created.'), 'success');

        return redirect(localized_route('gender-identities.index'));
    }

    public function edit(GenderIdentity $genderIdentity): View
    {
        return view('gender-identities.edit', [
            'genderIdentity' => $genderIdentity,
        ]);
    }

    public function update(UpdateGenderIdentityRequest $request, GenderIdentity $genderIdentity): RedirectResponse
    {
        $genderIdentity->update($request->validated());

        flash(__('The gender identity has been updated.'), 'success');

        return redirect(localized_route('gender-identities.index'));
    }

    public function destroy(GenderIdentity $genderIdentity): RedirectResponse
    {
        $genderIdentity->delete();

        flash(__('The gender identity has been deleted.'), 'success');

        return redirect(localized_route('gender-identities.index'));
    }
}
