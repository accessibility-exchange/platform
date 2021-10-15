<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

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
            'roleCollections' => Collection::whereIn('id', [1, 2, 3, 4])->get(),
            'stageCollections' => Collection::whereIn('id', [5, 6, 7])->get(),
            'otherCollections' => Collection::where('id', '>', 7)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            // TODO: Add stories
            'topics' => [
                'accessible-consultation' => __('Accessible consultation'),
                'intersectional-outreach' => __('Intersectional outreach'),
                'contracts' => __('Contracts'),
                'privacy' => __('Privacy'),
                'disability-knowledge' => __('Disability knowledge'),
            ],
            'types' => [
                'guidelines-and-best-practices' => __('Guidelines and best practices'),
                'practical-guides-and-how-tos' => __('Practical guides and how tos'),
                'stories-from-deaf-and-disability-communities' => __('Stories from Deaf and Disability communities'),
                'templates-and-forms' => __('Templates and forms'),
                'case-studies' => __('Case studies'),
            ],
            'formats' => [
                'text' => __('Text'),
                'video' => __('Video'),
                'audio' => __('Audio'),
                'pdf' => __('PDF'),
                'word' => __('Word document'),
            ],
            'languages' => [
                'en' => 'English',
                'fr' => 'Français',
            ],
            'process' => [
                'preparing-for-consultation' => __('Preparing for consultation'),
                'going-through-consultation' => __('Going through consultation'),
                'after-consultation' => __('After consultation and preparing reports'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
    }
}
