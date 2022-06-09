<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAreaTypeRequest;
use App\Http\Requests\UpdateAreaTypeRequest;
use App\Models\AreaType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AreaTypeController extends Controller
{
    public function index(): View
    {
        return view('area-types.index', [
            'areaTypes' => AreaType::all(),
        ]);
    }

    public function create(): View
    {
        return view('area-types.create');
    }

    public function store(StoreAreaTypeRequest $request): RedirectResponse
    {
        AreaType::create($request->validated());

        flash(__('The age bracket has been created.'), 'success');

        return redirect(localized_route('area-types.index'));
    }

    public function edit(AreaType $AreaType): View
    {
        return view('area-types.edit', [
            'AreaType' => $AreaType,
        ]);
    }

    public function update(UpdateAreaTypeRequest $request, AreaType $AreaType): RedirectResponse
    {
        AreaType::update($request->validated());

        flash(__('The age bracket has been updated.'), 'success');

        return redirect(localized_route('area-types.index'));
    }

    public function destroy(AreaType $AreaType): RedirectResponse
    {
        $AreaType->delete();

        flash(__('The age bracket has been updated.'), 'success');

        return redirect(localized_route('area-types.index'));
    }
}
