<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoryRequest;
use App\Http\Requests\DestroyStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use App\Models\Story;

class StoryController extends Controller
{
    /**
     * Display a listing of the story.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('stories.index', [
            'stories' => Story::orderBy('title')->get(),
            'topics' => Topic::all(),
            'formats' => Format::all(),
            'languages' => ['en', 'fr'],
            'phases' => Phase::all(),
        ]);
    }

    /**
     * Show the form for creating a new story.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Story::class);

        return view('stories.create');
    }

    /**
     * Store a newly created story in storage.
     *
     * @param  \App\Http\Requests\CreateStoryRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateStoryRequest $request)
    {
        $story = Story::create($request->validated());

        flash(__('story.create_succeeded'), 'success');

        return redirect(\localized_route('stories.show', ['story' => $story]));
    }

    /**
     * Display the specified story.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\View\View
     */
    public function show(Story $story)
    {
        return view('stories.show', ['story' => $story]);
    }

    /**
     * Show the form for editing the specified story.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\View\View
     */
    public function edit(Story $story)
    {
        return view('stories.edit', ['story' => $story]);
    }

    /**
     * Update the specified story in storage.
     *
     * @param  \App\Http\Requests\UpdateStoryRequest  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStoryRequest $request, Story $story)
    {
        $story->fill($request->validated());
        $story->save();

        flash(__('story.update_succeeded'), 'success');

        return redirect(\localized_route('stories.show', $story));
    }

    /**
     * Remove the specified story from storage.
     *
     * @param  \App\Http\Requests\DestroyStoryRequest  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyStoryRequest $request, Story $story)
    {
        $story->delete();

        flash(__('story.destroy_succeeded'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
