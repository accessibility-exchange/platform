<?php

namespace App\Http\Controllers;

use App\Models\Module;

class ModuleController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        return view('modules.show', [
            'course_title' => $module->course->title,
            'course_id' => $module->course->id,
            'title' => $module->title,
            'video' => $module->video,
            'description' => $module->description,
            'introduction' => $module->introduction,
            'quiz' => $module->quiz,
        ]);
    }
}
