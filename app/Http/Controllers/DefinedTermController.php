<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDefinedTermRequest;
use App\Http\Requests\UpdateDefinedTermRequest;
use App\Models\DefinedTerm;

class DefinedTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('defined-terms.index', [
            'terms' => DefinedTerm::all(),
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
     * @param  \App\Http\Requests\StoreDefinedTermRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDefinedTermRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DefinedTerm  $definedTerm
     * @return \Illuminate\Http\Response
     */
    public function edit(DefinedTerm $definedTerm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDefinedTermRequest  $request
     * @param  \App\Models\DefinedTerm  $definedTerm
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDefinedTermRequest $request, DefinedTerm $definedTerm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DefinedTerm  $definedTerm
     * @return \Illuminate\Http\Response
     */
    public function destroy(DefinedTerm $definedTerm)
    {
        //
    }
}
