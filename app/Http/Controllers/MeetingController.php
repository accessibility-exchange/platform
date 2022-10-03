<?php

namespace App\Http\Controllers;

use App\Enums\ProvinceOrTerritory;
use App\Enums\TimeZone;
use App\Http\Requests\MeetingRequest;
use App\Models\Engagement;
use App\Models\Meeting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\LaravelOptions\Options;

class MeetingController extends Controller
{
    public function create(Engagement $engagement): View
    {
        return view('meetings.edit', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'meeting' => new Meeting(['engagement_id' => $engagement->id]),
            'timezones' => Options::forEnum(TimeZone::class)->nullable(__('Please select your time zone…'))->toArray(),
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->nullable(__('Choose a province or territory…'))->toArray(),
        ]);
    }

    public function store(MeetingRequest $request, Engagement $engagement): RedirectResponse
    {
        $data = $request->validated();

        $data['engagement_id'] = $engagement->id;

        $meeting = Meeting::create($data);

        flash(__('Your meeting has been created.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function edit(Engagement $engagement, Meeting $meeting): View
    {
        return view('meetings.edit', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'meeting' => $meeting,
            'timezones' => Options::forEnum(TimeZone::class)->nullable(__('Please select your time zone…'))->toArray(),
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->nullable(__('Choose a province or territory…'))->toArray(),
        ]);
    }

    public function update(MeetingRequest $request, Engagement $engagement, Meeting $meeting): RedirectResponse
    {
        $data = $request->validated();

        $meeting->update($data);

        flash(__('Your meeting has been updated.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function destroy(Engagement $engagement, Meeting $meeting): RedirectResponse
    {
        $meeting->delete();

        flash(__('Your meeting has been deleted.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }
}
