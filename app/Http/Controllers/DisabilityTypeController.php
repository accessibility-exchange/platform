<?php

namespace App\Http\Controllers;

use App\Models\DisabilityType;
use Illuminate\Contracts\View\View;

class DisabilityTypeController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', DisabilityType::class);

        return view('disability-types.index', [
            'disabilityTypes' => DisabilityType::all(),
        ]);
    }

//    public function create(): View
//    {
//        return view('disability-types.create');
//    }
//
//    public function store(StoreDisabilityTypeRequest $request): RedirectResponse
//    {
//        DisabilityType::create($request->validated());
//
//        flash(__('The disability type has been created.'), 'success');
//
//        return redirect(localized_route('disability-types.index'));
//    }
//
//    public function edit(DisabilityType $disabilityType): View
//    {
//        return view('disability-types.edit', [
//            'disabilityType' => $disabilityType,
//        ]);
//    }
//
//    public function update(UpdateDisabilityTypeRequest $request, DisabilityType $disabilityType): RedirectResponse
//    {
//        $disabilityType->update($request->validated());
//
//        flash(__('The disability type has been updated.'), 'success');
//
//        return redirect(localized_route('disability-types.index'));
//    }
//
//    public function destroy(DisabilityType $disabilityType): RedirectResponse
//    {
//        $disabilityType->delete();
//
//        flash(__('The disability type has been deleted.'), 'success');
//
//        return redirect(localized_route('disability-types.index'));
//    }
}
