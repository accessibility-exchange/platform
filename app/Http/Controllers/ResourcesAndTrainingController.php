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
            'resourceCollections' => ResourceCollection::where('featured', true)->ordered()->get(),
            'totalResourceCollections' => ResourceCollection::count(),
            'libraries' => Library::where('featured', true)->ordered()->get(),
            'totalLibraries' => Library::count(),
            'courses' => Course::all(),
        ]);
    }
}
