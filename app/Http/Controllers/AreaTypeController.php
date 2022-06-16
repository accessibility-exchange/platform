<?php

namespace App\Http\Controllers;

use App\Models\AreaType;
use Illuminate\Contracts\View\View;

class AreaTypeController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', AreaType::class);

        return view('area-types.index', [
            'areaTypes' => AreaType::all(),
        ]);
    }

//    public function create(): View
//    {
//        return view('area-types.create');
//    }
//
//    public function store(StoreAreaTypeRequest $request): RedirectResponse
//    {
//        AreaType::create($request->validated());
//
//        flash(__('The area type has been created.'), 'success');
//
//        return redirect(localized_route('area-types.index'));
//    }
//
//    public function edit(AreaType $areaType): View
//    {
//        return view('area-types.edit', [
//            'AreaType' => $areaType,
//        ]);
//    }
//
//    public function update(UpdateAreaTypeRequest $request, AreaType $areaType): RedirectResponse
//    {
//        $areaType->update($request->validated());
//
//        flash(__('The area type has been updated.'), 'success');
//
//        return redirect(localized_route('area-types.index'));
//    }
//
//    public function destroy(AreaType $areaType): RedirectResponse
//    {
//        $areaType->delete();
//
//        flash(__('The area type has been deleted.'), 'success');
//
//        return redirect(localized_route('area-types.index'));
//    }
}
