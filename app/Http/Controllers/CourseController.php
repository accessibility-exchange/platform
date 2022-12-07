<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('courses.index', [
            'courses' => Course::all(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        $courseUser = $user->courses->where('id', $course->id)->first();
        $finishedCourse = $courseUser->pivot->finished_at ?? null;
        $receivedCertificate = $courseUser->pivot->received_certificate_at ?? null;

        return view('courses.show', [
            'user' => $user,
            'course' => $course,
            'modules' => $course->modules,
            'finishedCourse' => $finishedCourse && ! $receivedCertificate,
        ]);
    }
}
