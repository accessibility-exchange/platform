<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgeBracketRequest;
use App\Http\Requests\UpdateAgeBracketRequest;
use App\Models\AgeBracket;

class AgeBracketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreAgeBracketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgeBracketRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AgeBracket  $ageBracket
     * @return \Illuminate\Http\Response
     */
    public function show(AgeBracket $ageBracket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AgeBracket  $ageBracket
     * @return \Illuminate\Http\Response
     */
    public function edit(AgeBracket $ageBracket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAgeBracketRequest  $request
     * @param  \App\Models\AgeBracket  $ageBracket
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgeBracketRequest $request, AgeBracket $ageBracket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AgeBracket  $ageBracket
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgeBracket $ageBracket)
    {
        //
    }
}
