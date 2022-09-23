<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Models\Engagement;
use App\Models\Meeting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MeetingController extends Controller
{
    public function create(Engagement $engagement): View
    {
        //
    }

    public function store(StoreMeetingRequest $request, Engagement $engagement): RedirectResponse
    {
        //
    }

    public function show(Engagement $engagement, Meeting $meeting): View
    {
        //
    }

    public function edit(Engagement $engagement, Meeting $meeting): View
    {
        //
    }

    public function update(UpdateMeetingRequest $request, Engagement $engagement, Meeting $meeting): RedirectResponse
    {
        //
    }

    public function destroy(Engagement $engagement, Meeting $meeting): RedirectResponse
    {
        //
    }
}
