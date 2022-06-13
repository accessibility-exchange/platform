<?php

namespace App\Http\Controllers;

use App\Models\IndigenousIdentity;
use Illuminate\Contracts\View\View;

class IndigenousIdentityController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', IndigenousIdentity::class);

        return view('indigenous-identities.index', [
            'indigenousIdentities' => IndigenousIdentity::all(),
        ]);
    }

//    public function create(): View
//    {
//        return view('indigenous-identities.create');
//    }
//
//    public function store(StoreIndigenousIdentityRequest $request): RedirectResponse
//    {
//        IndigenousIdentity::create($request->validated());
//
//        flash(__('The indigenous identity has been created.'), 'success');
//
//        return redirect(localized_route('indigenous-identities.index'));
//    }
//
//    public function edit(IndigenousIdentity $indigenousIdentity): View
//    {
//        return view('indigenous-identities.edit', [
//            'indigenousIdentity' => $indigenousIdentity,
//        ]);
//    }
//
//    public function update(UpdateIndigenousIdentityRequest $request, IndigenousIdentity $indigenousIdentity): RedirectResponse
//    {
//        $indigenousIdentity->update($request->validated());
//
//        flash(__('The indigenous identity has been updated.'), 'success');
//
//        return redirect(localized_route('indigenous-identities.index'));
//    }
//
//    public function destroy(IndigenousIdentity $indigenousIdentity): RedirectResponse
//    {
//        $indigenousIdentity->delete();
//
//        flash(__('The indigenous identity has been deleted.'), 'success');
//
//        return redirect(localized_route('indigenous-identities.index'));
//    }
}
