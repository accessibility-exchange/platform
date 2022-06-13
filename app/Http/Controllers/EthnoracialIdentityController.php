<?php

namespace App\Http\Controllers;

use App\Models\EthnoracialIdentity;
use Illuminate\Contracts\View\View;

class EthnoracialIdentityController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', EthnoracialIdentity::class);

        return view('ethnoracial-identities.index', [
            'ethnoracialIdentities' => EthnoracialIdentity::all(),
        ]);
    }

//    public function create(): View
//    {
//        return view('ethnoracial-identities.create');
//    }
//
//    public function store(StoreEthnoracialIdentityRequest $request): RedirectResponse
//    {
//        EthnoracialIdentity::create($request->validated());
//
//        flash(__('The ethnoracial identity has been created.'), 'success');
//
//        return redirect(localized_route('ethnoracial-identities.index'));
//    }
//
//    public function edit(EthnoracialIdentity $ethnoracialIdentity): View
//    {
//        return view('ethnoracial-identities.edit', [
//            'ethnoracialIdentity' => $ethnoracialIdentity,
//        ]);
//    }
//
//    public function update(UpdateEthnoracialIdentityRequest $request, EthnoracialIdentity $ethnoracialIdentity): RedirectResponse
//    {
//        $ethnoracialIdentity->update($request->validated());
//
//        flash(__('The ethnoracial identity has been updated.'), 'success');
//
//        return redirect(localized_route('ethnoracial-identities.index'));
//    }
//
//    public function destroy(EthnoracialIdentity $ethnoracialIdentity): RedirectResponse
//    {
//        $ethnoracialIdentity->delete();
//
//        flash(__('The ethnoracial identity has been deleted.'), 'success');
//
//        return redirect(localized_route('ethnoracial-identities.index'));
//    }
}
