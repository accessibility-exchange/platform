<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Library;
use App\Models\ResourceCollection;

class ResourcesAndTrainingController extends Controller
{
    public function __invoke()
    {
        return view('resources-and-training', [
            'featuredResourceCollections' => ResourceCollection::where('featured', true)->ordered()->limit(4)->get(),
            'totalResourceCollections' => ResourceCollection::count(),
            'featuredLibraries' => Library::where('featured', true)->ordered()->limit(4)->get(),
            'totalLibraries' => Library::count(),
            'courses' => Course::all(),
        ]);
    }
}
