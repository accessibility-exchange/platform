<?php

namespace App\Http\Controllers;

use App\Models\AgeBracket;
use Illuminate\Contracts\View\View;

class AgeBracketController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', AgeBracket::class);

        return view('age-brackets.index', [
            'ageBrackets' => AgeBracket::all(),
        ]);
    }

//    public function create(): View
//    {
//        return view('age-brackets.create');
//    }
//
//    public function store(StoreAgeBracketRequest $request): RedirectResponse
//    {
//        AgeBracket::create($request->validated());
//
//        flash(__('The age bracket has been created.'), 'success');
//
//        return redirect(localized_route('age-brackets.index'));
//    }
//
//    public function edit(AgeBracket $ageBracket): View
//    {
//        return view('age-brackets.edit', [
//            'ageBracket' => $ageBracket,
//        ]);
//    }
//
//    public function update(UpdateAgeBracketRequest $request, AgeBracket $ageBracket): RedirectResponse
//    {
//        $ageBracket->update($request->validated());
//
//        flash(__('The age bracket has been updated.'), 'success');
//
//        return redirect(localized_route('age-brackets.index'));
//    }
//
//    public function destroy(AgeBracket $ageBracket): RedirectResponse
//    {
//        $ageBracket->delete();
//
//        flash(__('The age bracket has been deleted.'), 'success');
//
//        return redirect(localized_route('age-brackets.index'));
//    }
}
