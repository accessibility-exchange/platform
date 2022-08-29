<?php

namespace App\Http\Controllers;

use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\ProvinceOrTerritory;
use App\Http\Requests\StoreEngagementCriteriaRequest;
use App\Http\Requests\StoreEngagementLanguagesRequest;
use App\Http\Requests\StoreEngagementOutreachRequest;
use App\Http\Requests\StoreEngagementRecruitmentRequest;
use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementLanguagesRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Models\Engagement;
use App\Models\MatchingStrategy;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\LaravelOptions\Options;

class EngagementController extends Controller
{
    public function showLanguageSelection(Project $project): View
    {
        return view('engagements.show-language-selection', [
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'project' => $project,
        ]);
    }

    public function storeLanguages(StoreEngagementLanguagesRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        session()->put('languages', $data['languages']);

        return redirect(localized_route('engagements.create', $project));
    }

    public function create(Project $project): View
    {
        return view('engagements.create', [
            'project' => $project,
            'formats' => Options::forEnum(EngagementFormat::class)->toArray(),
        ]);
    }

    public function store(StoreEngagementRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        $data['languages'] = session('languages', $project->languages);

        $engagement = Engagement::create($data);

        $matchingStrategy = new MatchingStrategy();

        $engagement->matchingStrategy()->save($matchingStrategy);

        session()->forget('languages');

        flash(__('Your engagement has been created.'), 'success');

        return redirect(localized_route('engagements.show-outreach-selection', $engagement));
    }

    public function showOutreachSelection(Engagement $engagement): View
    {
        return view('engagements.show-outreach-selection', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }

    public function storeOutreach(StoreEngagementOutreachRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        $engagement = $engagement->fresh();

        $redirect = match ($engagement->who) {
            'organization' => localized_route('engagements.manage', $engagement),
            default => localized_route('engagements.show-recruitment-selection', $engagement),
        };

        return redirect($redirect);
    }

    public function showRecruitmentSelection(Engagement $engagement): View
    {
        return view('engagements.show-recruitment-selection', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'recruitments' => Options::forEnum(EngagementRecruitment::class)->toArray(),
        ]);
    }

    public function storeRecruitment(StoreEngagementRecruitmentRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(localized_route('engagements.show-criteria-selection', $engagement));
    }

    public function showCriteriaSelection(Engagement $engagement): View
    {
        return view('engagements.show-criteria-selection', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
        ]);
    }

    public function storeCriteria(StoreEngagementCriteriaRequest $request, Engagement $engagement): RedirectResponse
    {
        ray($request->validated());

//        $engagement->matchingStrategy->fill($request->validated());
//        $engagement->matchingStrategy->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(localized_route('engagements.show-criteria-selection', $engagement));

//        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function show(Engagement $engagement)
    {
        return view('engagements.show', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }

    public function edit(Engagement $engagement)
    {
        return view('engagements.edit', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }

    public function editLanguages(Engagement $engagement): View
    {
        return view('engagements.show-language-edit', [
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'engagement' => $engagement,
        ]);
    }

    public function updateLanguages(UpdateEngagementLanguagesRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement translations have been updated.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function update(UpdateEngagementRequest $request, Engagement $engagement)
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function manage(Engagement $engagement)
    {
        return view('engagements.manage', [
            'engagement' => $engagement,
            'project' => $engagement->project,
        ]);
    }

    public function participate(Engagement $engagement)
    {
        return view('engagements.participate', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }
}
