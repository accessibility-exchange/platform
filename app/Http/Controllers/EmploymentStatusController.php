<?php

namespace App\Http\Controllers;

use App\Models\EmploymentStatus;
use Illuminate\Contracts\View\View;

class EmploymentStatusController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', EmploymentStatus::class);

        return view('employment-statuses.index', [
            'employmentStatuses' => EmploymentStatus::all(),
        ]);
    }

//    public function create(): View
//    {
//        return view('employment-statuses.create');
//    }
//
//    public function store(StoreEmploymentStatusRequest $request): RedirectResponse
//    {
//        EmploymentStatus::create($request->validated());
//
//        flash(__('The employment status has been created.'), 'success');
//
//        return redirect(localized_route('employment-statuses.index'));
//    }
//
//    public function edit(EmploymentStatus $employmentStatus): View
//    {
//        return view('employment-statuses.edit', [
//            'disabilityIdentity' => $employmentStatus,
//        ]);
//    }
//
//    public function update(UpdateEmploymentStatusRequest $request, EmploymentStatus $employmentStatus): RedirectResponse
//    {
//        $employmentStatus->update($request->validated());
//
//        flash(__('The employment status has been updated.'), 'success');
//
//        return redirect(localized_route('employment-statuses.index'));
//    }
//
//    public function destroy(EmploymentStatus $employmentStatus): RedirectResponse
//    {
//        $employmentStatus->delete();
//
//        flash(__('The employment status has been deleted.'), 'success');
//
//        return redirect(localized_route('employment-statuses.index'));
//    }
}
