<?php

namespace App\Http\Controllers;

use App\Models\ContentType;
use App\Models\Format;
use App\Models\Phase;
use App\Models\ResourceCollection;
use App\Models\Topic;
use Illuminate\Contracts\View\View;

class ResourceCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $roleCollectionIds = settings()->get('roleCollectionIds', [1, 2, 3]);
        $topicCollectionIds = settings()->get('topicCollectionIds', [4, 5, 6, 7]);

        return view('resource-collections.index', [
            'roleCollections' => ResourceCollection::whereIn('id', $roleCollectionIds)->get(),
            'topicCollections' => ResourceCollection::whereIn('id', $topicCollectionIds)->get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  ResourceCollection  $resourceCollection
     * @return View
     */
    public function show(ResourceCollection $resourceCollection): View
    {
        return view('resource-collections.show', [
            'resourceCollection' => $resourceCollection,
            'resources' => $resourceCollection->resources,
            'topics' => Topic::all(),
            'types' => ContentType::all(),
            'formats' => Format::all(),
            'languages' => ['en', 'fr'],
            'phases' => Phase::all(),
        ]);
    }
}
