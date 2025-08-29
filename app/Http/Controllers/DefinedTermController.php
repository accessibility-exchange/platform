<?php

namespace App\Http\Controllers;

use App\Models\DefinedTerm;
use Illuminate\View\View;

class DefinedTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('defined-terms.index', [
            'terms' => DefinedTerm::all(),
        ]);
    }
}
