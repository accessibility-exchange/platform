<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ResourceCollection;
use Illuminate\Contracts\View\View;

class ResourceCollectionController extends Controller
{
    public function index(): View
    {
        return view('resource-collections.index', [
            'resourceCollections' => ResourceCollection::orderBy('title')->get(),
            'courses' => Course::all(),
        ]);
    }
}
