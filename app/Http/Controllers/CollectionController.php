<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ContentType;
use App\Models\Format;
use App\Models\Phase;
use App\Models\Topic;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roleCollectionIds = settings()->get('roleCollectionIds', [1, 2, 3]);
        $topicCollectionIds = settings()->get('topicCollectionIds', [4, 5, 6, 7]);

        return view('collections.index', [
            'roleCollections' => Collection::whereIn('id', $roleCollectionIds)->get(),
            'topicCollections' => Collection::whereIn('id', $topicCollectionIds)->get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\View\View
     */
    public function show(Collection $collection)
    {
        return view('collections.show', [
            'collection' => $collection,
            'resources' => $collection->resources,
            'topics' => Topic::all(),
            'types' => ContentType::all(),
            'formats' => Format::all(),
            'languages' => ['en', 'fr'],
            'phases' => Phase::all(),
        ]);
    }
}
