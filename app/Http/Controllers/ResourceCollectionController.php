<?php

namespace App\Http\Controllers;

use App\Enums\ResourceFormat;
use App\Http\Requests\CreateResourceCollectionRequest;
use App\Http\Requests\DestroyResourceCollectionRequest;
use App\Http\Requests\UpdateResourceCollectionRequest;
use App\Models\ContentType;
use App\Models\Phase;
use App\Models\ResourceCollection;
use App\Models\Topic;
use Spatie\LaravelOptions\Options;

class ResourceCollectionController extends Controller
{
    /**
     * Display a listing of the resource collection.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('resource-collections.index', ['resourceCollections' => ResourceCollection::orderBy('title')->get()]);
    }

    /**
     * Show the form for creating a new resource collection.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', ResourceCollection::class);

        return view('resource-collections.create', [
            'resourceCollectionId' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateResourceCollectionRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateResourceCollectionRequest $request)
    {
        $data = $request->validated();

        $resourceCollection = ResourceCollection::create($data);

        $resourceCollection->resources()->attach($data['resource_ids']);

        flash(__('resource-collection.create_succeeded'), 'success');

        return redirect(\localized_route('resource-collections.show', ['resourceCollection' => $resourceCollection]));
    }

    /**
     * Display the specified resource collection.
     *
     * @param  \App\Models\ResourceCollection  $resourceCollection
     * @return \Illuminate\View\View
     */
    public function show(ResourceCollection $resourceCollection)
    {
        return view('resource-collections.show', [
            'resourceCollection' => $resourceCollection,
            'resources' => $resourceCollection->resources,
            'topics' => Topic::all(),
            'types' => ContentType::all(),
            'formats' => Options::forEnum(ResourceFormat::class)->toArray(),
            'languages' => ['en', 'fr'],
            'phases' => Phase::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource collection.
     *
     * @param  \App\Models\ResourceCollection  $resourceCollection
     * @return \Illuminate\View\View
     */
    public function edit(ResourceCollection $resourceCollection)
    {
        return view('resource-collections.edit', [
            'resourceCollection' => $resourceCollection,
            'resourceCollectionId' => $resourceCollection->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResourceCollectionRequest  $request
     * @param  \App\Models\ResourceCollection  $resourceCollection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateResourceCollectionRequest $request, ResourceCollection $resourceCollection)
    {
        $resourceCollection->fill($request->validated());
        $resourceCollection->resources()->sync($request['resource_ids']);
        $resourceCollection->save();

        flash(__('resource-collection.update_succeeded'), 'success');

        return redirect(\localized_route('resource-collections.show', $resourceCollection));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyResourceCollectionRequest  $request
     * @param  \App\Models\ResourceCollection  $resourceCollection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyResourceCollectionRequest $request, ResourceCollection $resourceCollection)
    {
        $resourceCollection->delete();

        flash(__('resource-collection.destroy_succeeded'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
