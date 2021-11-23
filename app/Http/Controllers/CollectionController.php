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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('collections.index', [
            // TODO: Handle this better.
            'roleCollections' => Collection::whereIn('id', [1, 2, 3])->get(),
            'topicCollections' => Collection::whereIn('id', [4, 5, 6, 7])->get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
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
