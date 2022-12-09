<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Contracts\View\View;

class ResourceController extends Controller
{
    public function show(Resource $resource): View
    {
        return view('resources.show', ['resource' => $resource]);
    }
}
