<?php

namespace App\Http\Controllers;

use App\Models\DefinedTerm;

class DefinedTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('defined-terms.index', [
            'terms' => DefinedTerm::all(),
        ]);
    }
}
