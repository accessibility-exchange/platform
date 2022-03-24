<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyResourceRequest;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\ContentType;
use App\Models\Format;
use App\Models\Phase;
use App\Models\Resource;
use App\Models\Topic;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('resources.index', [
            'resources' => Resource::orderBy('title')->paginate(20),
            'topics' => Topic::all(),
            'types' => ContentType::all(),
            'formats' => Format::all(),
            'languages' => ['en', 'fr'],
            'phases' => Phase::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Resource::class);

        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResourceRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreResourceRequest $request)
    {
        $resource = Resource::create($request->validated());

        flash(__('resource.create_succeeded'), 'success');

        return redirect(\localized_route('resources.show', ['resource' => $resource]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\View\View
     */
    public function show(Resource $resource)
    {
        return view('resources.show', ['resource' => $resource]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\View\View
     */
    public function edit(Resource $resource)
    {
        return view('resources.edit', ['resource' => $resource]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResourceRequest  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $resource->fill($request->validated());
        $resource->save();

        flash(__('resource.update_succeeded'), 'success');

        return redirect(\localized_route('resources.show', $resource));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyResourceRequest  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyResourceRequest $request, Resource $resource)
    {
        $resource->delete();

        flash(__('resource.destroy_succeeded'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
