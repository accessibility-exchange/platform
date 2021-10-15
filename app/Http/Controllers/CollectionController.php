<?php

namespace App\Http\Controllers;

use App\Models\Collection;

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
            'roleCollections' => Collection::whereIn('id', [1, 2, 3, 4])->get(),
            'stageCollections' => Collection::whereIn('id', [5, 6, 7])->get(),
            'otherCollections' => Collection::where('id', '>', 7)->get(),
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
}
