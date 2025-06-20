<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Contracts\View\View;

class LibraryController extends Controller
{
    public function show(Library $library): View
    {
        $library->load('resourceCollections');

        return view('libraries.show', compact('library'));
    }
}
